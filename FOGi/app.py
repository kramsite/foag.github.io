# app.py — FOGi + Ollama (Windows-safe)
# Salva em: C:\xampp\htdocs\foag.github.io\Fogi\app.py

import os
import json
import time
import subprocess
import traceback
from pathlib import Path
from flask import Flask, render_template
from flask_socketio import SocketIO

# ---------------- CONFIG ----------------
TEMPLATE_NAME = "FOGi.html"
OLLAMA_MODEL = "llama3"        # ou "phi3" se preferir
OLLAMA_TIMEOUT = 60
KB_PATH = Path("kb.json")
# ----------------------------------------

app = Flask(__name__, template_folder="templates", static_folder="static")
app.config["SECRET_KEY"] = os.getenv("SECRET_KEY", "dev_secret_key")
app.debug = True

socketio = SocketIO(app, cors_allowed_origins="*", async_mode="threading")

KB = [
    {"tags": ["ods", "ods4", "educacao"], "answer": "ODS 4: garantir educação inclusiva e de qualidade."},
    {"tags": ["estudo", "rotina"], "answer": "Use Pomodoro: 25min foco + 5min pausa."},
    {"tags": ["enem", "redacao"], "answer": "Redação: tese clara + dois argumentos + proposta de intervenção."}
]

# ---------------- AUXILIARES ----------------
def simple_router(text: str) -> str:
    t = text.lower()
    if any(x in t for x in ["oi", "ola", "olá", "bom dia", "boa tarde", "boa noite"]):
        return "Oi! Eu sou a FOGi — tutora virtual do FOAG. Pergunte sobre estudos ou ODS 4."
    for item in KB:
        if any(tag in t for tag in item["tags"]):
            return item["answer"]
    return "Não entendi muito bem. Fala o tema e o objetivo do estudo pra eu te ajudar melhor."

def ollama_generate_cli(model: str, prompt: str, timeout_sec: int = OLLAMA_TIMEOUT) -> str:
    full_prompt = (
        "Você é a FOGi, tutora de estudos e ODS 4 em Cuiabá. "
        "Seu tom é leve, direto e amigável (Gen Z). "
        "Explique de forma clara e curta, e finalize com uma micro-atividade prática.\n\n"
        f"Pergunta do usuário: {prompt}\n\nResposta:"
    )

    args = ["ollama", "run", model, "--prompt", full_prompt]
    try:
        print(f"[OLLAMA] Executando: {' '.join(args)}")
        proc = subprocess.run(
            args,
            capture_output=True,
            text=True,
            timeout=timeout_sec,
            shell=False
        )
        if proc.returncode == 0:
            out = (proc.stdout or "").strip()
            if not out:
                out = (proc.stderr or "").strip() or "Sem resposta do modelo."
            return out
        else:
            stderr = (proc.stderr or "").strip()
            stdout = (proc.stdout or "").strip()
            return f"[ERRO OLLAMA CLI] {stderr or stdout}"
    except FileNotFoundError:
        return "[ERRO] Ollama não encontrado. Reabra o terminal ou reinstale."
    except subprocess.TimeoutExpired:
        return "[ERRO] Tempo esgotado. Aumente OLLAMA_TIMEOUT."
    except Exception as e:
        return f"[EXCEÇÃO OLLAMA] {e}"

def stream_text_emit(text: str):
    try:
        chunk = ""
        for ch in text:
            chunk += ch
            if len(chunk) >= 12:
                socketio.emit("assistant_chunk", {"text": chunk})
                chunk = ""
                time.sleep(0.01)
        if chunk:
            socketio.emit("assistant_chunk", {"text": chunk})
        socketio.emit("assistant_done", {"full": text})
    except Exception:
        traceback.print_exc()

# ---------------- ROTAS ----------------
@app.route("/")
def index():
    try:
        tpl_path = Path(app.template_folder) / TEMPLATE_NAME
        if not tpl_path.exists():
            return f"<h3>Arquivo {TEMPLATE_NAME} não encontrado em {tpl_path}</h3>", 500
        return render_template(TEMPLATE_NAME)
    except Exception:
        traceback.print_exc()
        return "<h3>Erro ao renderizar template</h3>", 500

# ---------------- SOCKET ----------------
@socketio.on("user_message")
def handle_user_message(data):
    try:
        user_text = (data or {}).get("text", "").strip()
        if not user_text:
            socketio.emit("assistant_error", {"message": "Mensagem vazia."})
            return

        print(f"[MSG] Usuário: {user_text}")
        reply = ollama_generate_cli(OLLAMA_MODEL, user_text, timeout_sec=OLLAMA_TIMEOUT)

        if reply.startswith("[ERRO") or reply.startswith("[EXCE"):
            print(f"[OLLAMA] fallback: {reply}")
            reply = simple_router(user_text)

        stream_text_emit(reply)

    except Exception:
        traceback.print_exc()
        socketio.emit("assistant_error", {"message": "Erro interno."})

# ---------------- RUN ----------------
if __name__ == "__main__":
    print("Rodando FOGi (Flask + SocketIO)")
    print(f"Template: {TEMPLATE_NAME} | Modelo: {OLLAMA_MODEL}")
    print("Teste rápido: ollama run llama3 --prompt \"oi\"")
    socketio.run(app, host="0.0.0.0", port=5000, debug=True)

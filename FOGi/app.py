# app.py — FOGi + Ollama (versão final, compatível Windows)
# Caminho: C:\xampp\htdocs\foag.github.io\Fogi\app.py

import os
import json
import time
import subprocess
import traceback
from pathlib import Path
from flask import Flask, render_template
from flask_socketio import SocketIO

# ------------------------- CONFIG -------------------------
TEMPLATE_NAME = "FOGi.html"    # arquivo do seu templates/
OLLAMA_MODEL = "llama3"        # troque para "phi3" se preferir
OLLAMA_TIMEOUT = 60            # tempo máximo pra IA responder
KB_PATH = Path("kb.json")      # fallback opcional
# ----------------------------------------------------------

app = Flask(__name__, template_folder="templates", static_folder="static")
app.config["SECRET_KEY"] = "dev_secret"
socketio = SocketIO(app, cors_allowed_origins="*", async_mode="threading")

# ------------------------ FALLBACK KB -----------------------
KB = [
    {"tags": ["ods", "ods4"], "answer": "ODS 4 é sobre educação inclusiva e de qualidade."},
    {"tags": ["estudo"], "answer": "Dica rápida: 25–40 min de foco + 5 min pausa."},
    {"tags": ["enem"], "answer": "Redação: tese clara, 2 argumentos + proposta final."}
]

def simple_router(text):
    t = text.lower()
    if any(x in t for x in ["oi", "ola", "olá", "bom dia", "boa tarde", "boa noite"]):
        return "Oi! Eu sou a FOGi — tua tutora virtual. Pode mandar dúvidas sobre estudos, matemática, escrita ou ODS 4."
    for item in KB:
        if any(tag in t for tag in item["tags"]):
            return item["answer"]
    return "Me explica rapidinho o tema e o objetivo do estudo que eu monto algo pra você."

# ------------------- OLLAMA INTEGRAÇÃO ---------------------
def ollama_generate_cli(model: str, prompt: str, timeout_sec: int = OLLAMA_TIMEOUT) -> str:
    """
    Envia o prompt via STDIN — compatível com Windows, com UTF-8 e regras da FOGi.
    """
    full_prompt = f"""
Você é a FOGi, IA educacional do projeto FOAG, em Cuiabá (MT).

INSTRUÇÕES IMPORTANTES:
- "FOGi" é apenas o SEU nome como assistente virtual do FOAG.
- NÃO invente que FOGi é fundo financeiro, bolsa, instituição ou sigla tipo "Fundo de Oportunidades de Graduação".
- Se alguém perguntar "o que é FOGi?", responda que é a IA/tutora virtual do FOAG que ajuda estudantes a estudar.

Jeito de falar:
- Sempre em português brasileiro.
- Tom leve, direto e amigável, tipo monitora gente boa.
- Nada de textão enrolado, seja clara e objetiva.
- Não fique se apresentando toda hora; foque na dúvida da pessoa.

Quando responder:
- Explique o conteúdo de forma simples.
- Se for matemática, mostre o passo a passo com um exemplo numérico.
- Se for redação/ENEM, ajude com tese + ideias de argumentos.
- No final, sugira 1 micro-atividade prática rápida (algo que dê pra fazer em ~5–10 minutos).

Pergunta da aluna:
{prompt}

Agora responda seguindo as instruções acima, sem repetir esse prompt.
""".strip()

    args = ["ollama", "run", model]

    try:
        print(f"[OLLAMA] Executando (stdin): {' '.join(args)}")

        proc = subprocess.run(
            args,
            input=full_prompt,
            text=True,
            capture_output=True,
            timeout=timeout_sec,
            shell=False,
            encoding="utf-8",   # força UTF-8 pra não zoar acento
            errors="replace"
        )

        if proc.returncode == 0:
            out = (proc.stdout or "").strip()
            if not out:
                out = (proc.stderr or "").strip() or "Sem resposta."
            print(f"[OLLAMA RAW]: {repr(out)}")
            return out

        stderr = (proc.stderr or "").strip()
        stdout = (proc.stdout or "").strip()
        return f"[ERRO OLLAMA] {stderr or stdout}"

    except FileNotFoundError:
        return "[ERRO] Ollama não encontrado no PATH."
    except subprocess.TimeoutExpired:
        return "[ERRO] Modelo demorou demais."
    except Exception as e:
        return f"[EXCEÇÃO] {e}"

# ----------------------- STREAM ----------------------------
def stream_text_emit(text: str):
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

# ----------------------- ROTAS -----------------------------
@app.route("/")
def index():
    try:
        return render_template(TEMPLATE_NAME)
    except Exception:
        traceback.print_exc()
        return "<h2>Erro ao carregar FOGi.html</h2>", 500

# ----------------------- SOCKET ----------------------------
@socketio.on("user_message")
def handle_user_message(data):
    try:
        user_text = (data or {}).get("text", "").strip()
        if not user_text:
            socketio.emit("assistant_error", {"message": "Mensagem vazia."})
            return

        print(f"[MSG] Usuário: {user_text}")

        # tenta IA real
        reply = ollama_generate_cli(OLLAMA_MODEL, user_text)

        # se deu erro, usa fallback
        if reply.startswith("[ERRO") or reply.startswith("[EXCE"):
            print("[FOGi] fallback ativado")
            reply = simple_router(user_text)

        stream_text_emit(reply)

    except Exception as e:
        traceback.print_exc()
        socketio.emit("assistant_error", {"message": f"Erro interno: {e}"})


# ------------------------ RUN -------------------------------
if __name__ == "__main__":
    print("=== FOGi rodando ===")
    print(f"Modelo: {OLLAMA_MODEL}")
    print("Abra: http://127.0.0.1:5000")
    socketio.run(app, host="0.0.0.0", port=5000, debug=True)

# app.py — FOGi + Ollama (versão robusta, compatível Windows)
# Caminho sugerido: C:\xampp\htdocs\foag.github.io\FOGi\app.py

import os
import json
import time
import subprocess
import traceback
from pathlib import Path

from flask import Flask, render_template
from flask_socketio import SocketIO

# ------------------------- CONFIG -------------------------

# Nome do template HTML (dentro da pasta "templates")
TEMPLATE_NAME = "FOGi.html"

# Nome do modelo Ollama (definido no Modelfile com FROM ...)
# Ex.: "fogi", "qwen2.5:7b-instruct", etc.
OLLAMA_MODEL = "fogi"

# Tempo máximo de espera da resposta do modelo (segundos)
OLLAMA_TIMEOUT = 60

# Caminho opcional para base de conhecimento simples em JSON
KB_PATH = Path("kb.json")

# ----------------------------------------------------------

app = Flask(__name__, template_folder="templates", static_folder="static")
app.config["SECRET_KEY"] = os.getenv("FOGI_SECRET_KEY", "dev_secret")

# Usa threading para evitar problemas com eventlet no Windows/Python novo
socketio = SocketIO(app, cors_allowed_origins="*", async_mode="threading")

# ------------------------ FALLBACK KB -----------------------

# KB inicial (pode ser sobrescrita pelo kb.json)
KB = [
    {
        "tags": ["ods", "ods4"],
        "answer": "ODS 4 é sobre garantir educação inclusiva, equitativa e de qualidade ao longo da vida."
    },
    {
        "tags": ["estudo", "rotina", "organizar"],
        "answer": "Dica rápida: 25–40 min de foco + 5 min de pausa ajudam bastante. Use isso para montar blocos de estudo."
    },
    {
        "tags": ["enem", "redação", "redacao"],
        "answer": (
            "Redação padrão ENEM: introdução com tese clara, 2 parágrafos de desenvolvimento com argumentos bem explicados "
            "e um parágrafo final com proposta de intervenção detalhada (agente, ação, meio, efeito e detalhamento)."
        )
    }
]


def load_kb_from_json():
    """Carrega KB de um arquivo kb.json, se existir e estiver bem formatado."""
    global KB
    if KB_PATH.exists():
        try:
            with KB_PATH.open("r", encoding="utf-8") as f:
                kb_loaded = json.load(f)
            if isinstance(kb_loaded, list) and kb_loaded:
                KB = kb_loaded
                print(f"[KB] Carregado {len(KB)} itens de {KB_PATH}")
            else:
                print("[KB] kb.json encontrado, mas vazio ou em formato inesperado. Usando KB padrão.")
        except Exception:
            print("[KB] Erro ao ler kb.json:")
            traceback.print_exc()


load_kb_from_json()


def simple_router(text: str) -> str:
    """
    Fallback simples caso o Ollama quebre ou não esteja disponível.
    Não é uma IA, mas tenta responder algo útil baseado em palavras-chave.
    """
    t = (text or "").lower().strip()

    # Saudações
    if any(x in t for x in ["oi", "ola", "olá", "bom dia", "boa tarde", "boa noite"]):
        return (
            "Oi! Eu sou a FOGi — tua tutora virtual de estudos. "
            "Pode mandar dúvidas sobre matérias da escola, ENEM, redação, organização de estudo ou o que você estiver aprendendo."
        )

    # Caso específico: tema ENEM sobre envelhecimento no Brasil
    if "envelhecimento" in t and "brasil" in t:
        return (
            "Tema: perspectivas para o envelhecimento no Brasil.\n\n"
            "Possível tese:\n"
            "- Embora o envelhecimento da população brasileira represente um avanço social, a falta de políticas públicas estruturadas "
            "compromete a dignidade e a qualidade de vida das pessoas idosas.\n\n"
            "Argumento 1 — Saúde e estrutura:\n"
            "- Muitos idosos enfrentam filas no SUS, baixa oferta de geriatras e dificuldades de acesso a medicamentos e serviços especializados.\n"
            "- Você pode citar dados do IBGE mostrando o aumento da expectativa de vida e o crescimento da população idosa.\n\n"
            "Argumento 2 — Renda, trabalho e vulnerabilidade social:\n"
            "- Aposentadorias muitas vezes não cobrem o custo de vida, fazendo com que alguns idosos continuem trabalhando de forma informal.\n"
            "- Dá para discutir desigualdade social, abandono familiar e falta de redes de apoio.\n\n"
            "Repertórios possíveis:\n"
            "- IBGE: projeções de envelhecimento da população brasileira.\n"
            "- Estatuto do Idoso (Lei nº 10.741/2003).\n"
            "- Constituição Federal: princípio da dignidade da pessoa humana.\n"
            "- Filmes ou séries que mostrem idosos em situação de abandono ou solidão.\n\n"
            "Proposta de intervenção (modelo ENEM):\n"
            "- Agente: Governo Federal, em parceria com estados e municípios.\n"
            "- Ação: ampliar programas de atenção integral ao idoso, com equipes multidisciplinares em saúde e assistência social.\n"
            "- Meio: investimento em unidades básicas de saúde, centros de convivência e campanhas educativas contra o etarismo.\n"
            "- Efeito: melhoria da qualidade de vida, redução da vulnerabilidade social e valorização da pessoa idosa.\n\n"
            "Micro-atividade: escreva uma introdução com essa tese e escolha 2 repertórios para encaixar nos parágrafos de desenvolvimento."
        )

    # Match por tags na KB
    for item in KB:
        tags = [tag.lower() for tag in item.get("tags", [])]
        if any(tag in t for tag in tags):
            return item.get("answer", "")

    # Fallback genérico
    return (
        "Pra te ajudar melhor, me diz:\n"
        "- Matéria (ex.: matemática, redação, história)\n"
        "- Objetivo (prova, ENEM, trabalho, revisão)\n"
        "- Prazo (quando é a prova/trabalho)\n"
        "Com isso eu consigo organizar uma explicação ou mini plano pra você."
    )


# ------------------- OLLAMA INTEGRAÇÃO ---------------------


def ollama_generate_cli(model: str, prompt: str, timeout_sec: int = OLLAMA_TIMEOUT) -> str:
    """
    Chama: ollama run <modelo>
    Manda o prompt via STDIN.
    O SYSTEM da FOGi já está definido no Modelfile do modelo 'fogi'.
    """
    user_prompt = f"Aluno: {prompt}\nFOGi:"

    args = ["ollama", "run", model]
    try:
        print(f"[OLLAMA] Executando (stdin): {' '.join(args)}")
        proc = subprocess.run(
            args,
            input=user_prompt,
            capture_output=True,
            text=True,
            encoding="utf-8",   # garante acentuação correta
            timeout=timeout_sec,
            shell=False
        )

        if proc.returncode == 0:
            out = (proc.stdout or "").strip()
            if not out:
                out = (proc.stderr or "").strip() or "Sem resposta do modelo."
            return out
        else:
            err = (proc.stderr or "").strip()
            stdout = (proc.stdout or "").strip()
            return f"[ERRO OLLAMA CLI] {err or stdout}"

    except FileNotFoundError:
        return "[ERRO] Ollama não encontrado. Verifique se o Ollama está instalado e no PATH."
    except subprocess.TimeoutExpired:
        return "[ERRO] Tempo esgotado ao chamar o modelo. Considere aumentar OLLAMA_TIMEOUT."
    except Exception as e:
        return f"[EXCEÇÃO OLLAMA] {e}"


# ----------------------- STREAM ----------------------------


def stream_text_emit(text: str) -> None:
    """
    Envia a resposta em pequenos pedaços para o front-end,
    criando um efeito de "digitando".
    """
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
        print("[STREAM] Erro ao enviar texto em chunks:")
        traceback.print_exc()


# ----------------------- ROTAS HTTP ------------------------


@app.route("/")
def index():
    """
    Rota principal: renderiza o template da FOGi.
    """
    try:
        template_path = Path(app.template_folder or "templates") / TEMPLATE_NAME
        if not template_path.exists():
            return (
                f"<h2>Template não encontrado: {TEMPLATE_NAME}</h2>"
                f"<p>Verifique se o arquivo existe em: {template_path}</p>",
                500,
            )
        return render_template(TEMPLATE_NAME)
    except Exception:
        traceback.print_exc()
        return "<h2>Erro ao carregar FOGi.html</h2>", 500


# ----------------------- SOCKET.IO -------------------------


@socketio.on("user_message")
def handle_user_message(data):
    """
    Recebe mensagem do usuário via Socket.IO,
    manda para o modelo (ou fallback) e devolve via streaming.
    """
    try:
        user_text = (data or {}).get("text", "")
        user_text = user_text.strip()

        if not user_text:
            socketio.emit("assistant_error", {"message": "Mensagem vazia."})
            return

        print(f"[MSG] Usuário: {user_text!r}")

        # 1) Tenta IA local (Ollama)
        reply = ollama_generate_cli(OLLAMA_MODEL, user_text)

        # 2) Se houve erro na chamada, usa fallback simples
        if reply.startswith("[ERRO") or reply.startswith("[EXCE"):
            print(f"[FOGi] Fallback ativado. Motivo: {reply}")
            reply = simple_router(user_text)

        # 3) Envia resposta (streaming)
        stream_text_emit(reply)

    except Exception as e:
        print("=== ERRO NO HANDLER user_message ===")
        traceback.print_exc()
        socketio.emit("assistant_error", {"message": f"Erro interno na FOGi: {e}"})


# ------------------------ RUN ------------------------------


if __name__ == "__main__":
    print("=== FOGi rodando ===")
    print(f"Modelo Ollama: {OLLAMA_MODEL}")
    print("Acesse: http://127.0.0.1:5000")
    socketio.run(app, host="0.0.0.0", port=5000, debug=True)

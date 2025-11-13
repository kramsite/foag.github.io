import time
from flask import Flask, render_template
from flask_socketio import SocketIO

app = Flask(__name__, template_folder="templates", static_folder="static")
app.config["SECRET_KEY"] = "dev"

# Usar threading evita problemas com eventlet em Python 3.14
socketio = SocketIO(app, cors_allowed_origins="*", async_mode="threading")

FOGI_INTRO = (
    "Oi! Eu sou a FOGi, tutora grátis da ODS 4. Manda a dúvida de estudo ou de educação "
    "e eu respondo curtinho, direto e com 1 atividade rápida."
)

KB = [
    {"tags": ["ods", "ods4", "educação"], "answer": "ODS 4 = educação de qualidade. Diga o tema e eu resumo."},
    {"tags": ["cuiabá", "mt"], "answer": "Contexto Cuiabá: infraestrutura, calor, projetos de leitura. Diga a escola/nível."},
    {"tags": ["estudo", "rotina", "pomodoro"], "answer": "Tenta 25–40min estudo + 5min pausa. Me diz prazo e eu monto um plano."},
    {"tags": ["enem", "redação"], "answer": "ENEM: interpretação + gestão do tempo. Quer mini-plano pra redação?"},
    {"tags": ["fontes", "referências"], "answer": "Fontes úteis: UNESCO, MEC/INEP, IBGE. Quer links pra tema X?"}
]

def simple_router(text: str) -> str:
    t = text.lower()
    if any(w in t for w in ["oi", "olá", "bom dia", "boa tarde", "boa noite"]):
        return FOGI_INTRO
    if "plano" in t or "cronograma" in t:
        return "Plano 7 dias: 1-2 teoria, 3-4 exercícios, 5 revisão, 6 simulado curto, 7 revisar erros. Me diz matéria."
    words = set(t.replace(",", " ").replace(".", " ").split())
    best = None; best_score = 0
    for item in KB:
        score = len(set(item["tags"]) & words)
        if score > best_score:
            best_score = score; best = item
    if best:
        return best["answer"]
    return "Ficou vago. Diz: tema + objetivo (trabalho/prova) e data. Eu já te mando mini-plano."

def stream_emit(text: str):
    chunk = ""
    for ch in text:
        chunk += ch
        if len(chunk) >= 10:
            socketio.emit("assistant_chunk", {"text": chunk})
            chunk = ""
            time.sleep(0.01)
    if chunk:
        socketio.emit("assistant_chunk", {"text": chunk})
    socketio.emit("assistant_done", {"full": text})

@app.route("/")
def index():
    return render_template("FOGi.html")


@socketio.on("user_message")
def handle_user_message(data):
    user_text = (data or {}).get("text", "").strip()
    if not user_text:
        socketio.emit("assistant_error", {"message": "Mensagem vazia."})
        return
    reply = simple_router(user_text)
    stream_emit(reply)

if __name__ == "__main__":
    # threading mode => não precisa de eventlet/gevent; bom pra dev local
    socketio.run(app, host="0.0.0.0", port=5000)

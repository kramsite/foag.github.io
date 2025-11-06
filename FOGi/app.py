import os
from flask import Flask, render_template, request
from flask_socketio import SocketIO, emit
from dotenv import load_dotenv
from openai import OpenAI

load_dotenv()
app = Flask(__name__, static_folder="static", template_folder="templates")
app.config["SECRET_KEY"] = os.getenv("SECRET_KEY", "dev")
# Use eventlet if available (installed)
socketio = SocketIO(app, cors_allowed_origins="*")
client = OpenAI(api_key=os.getenv("OPENAI_API_KEY"))

FOGI_SYSTEM = """Você é a FOGi, tutora de estudos do projeto FOAG focada na ODS 4 (Educação de Qualidade) em Cuiabá.
Estilo: clara, encorajadora, sem enrolação. Dá passos curtos, checa entendimento e sugere exercícios.
Regras:
- Se o tema fugir de educação/estudos/ODS, responda brevemente e puxe de volta para estudo.
- Para dúvidas de conteúdo, responda em tópicos e proponha 1 atividade rápida.
- Contextualize com Cuiabá/MT quando fizer sentido (dados locais, redes públicas, realidades escolares).
- Se pedir fontes, entregue 2–4 fontes confiáveis (INEP, MEC, UNESCO, IBGE).
- Linguagem inclusiva e acessível.
"""

def stream_openai_reply(user_text):
    # Streaming via Responses API (openai>=2.x)
    with client.responses.stream(
        model="gpt-5",
        input=[
            {"role": "system", "content": FOGI_SYSTEM},
            {"role": "user", "content": user_text}
        ],
        max_output_tokens=800,
    ) as stream:
        for event in stream:
            if event.type == "response.output_text.delta":
                chunk = event.delta
                socketio.emit("assistant_chunk", {"text": chunk})
            elif event.type == "response.error":
                socketio.emit("assistant_error", {"message": str(event.error)})
        # end streaming
        final = stream.get_final_response()
        # Flatten final text for 'assistant_done'
        full_text_parts = []
        for obj in final.output:
            for piece in obj.get("content", []):
                if piece.get("type") == "output_text" and "text" in piece:
                    full_text_parts.append(piece["text"])
        full_text = "".join(full_text_parts)
        socketio.emit("assistant_done", {"full": full_text})

@app.route("/")
def index():
    return render_template("index.html")

@socketio.on("user_message")
def handle_user_message(data):
    user_text = (data or {}).get("text", "").strip()
    if not user_text:
        emit("assistant_error", {"message": "Mensagem vazia."})
        return
    socketio.start_background_task(stream_openai_reply, user_text)

if __name__ == "__main__":
    # eventlet chosen automatically if installed
    socketio.run(app, host="0.0.0.0", port=5000)

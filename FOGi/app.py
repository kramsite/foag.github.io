from flask import Flask, render_template
from flask_socketio import SocketIO, emit
import os
import json
import time
import subprocess
import traceback

app = Flask(__name__)
app.config['SECRET_KEY'] = 'connectai-local'
socketio = SocketIO(app)

OLLAMA_URL = "http://localhost:11434/api/generate"

@app.route('/')
def index():
    return render_template('FOGi.html.html')

def gerar_resposta(mensagem):
    prompt = (
        "Você é a ConnectAI, uma assistente virtual empática do projeto ODS do Senac "
        "sobre Saúde e Bem-Estar em Cuiabá. Dê respostas curtas, gentis e informativas.\n\n"
        f"Usuário: {mensagem}\nConnectAI:"
    )

    payload = {
        "model": "llama3",
        "prompt": prompt,
        "stream": False
    }

    try:
        response = requests.post(OLLAMA_URL, json=payload)
        data = response.json()
        return data.get("response", "Desculpe, não consegui entender.")
    except Exception as e:
        return f"Erro ao gerar resposta: {e}"

@socketio.on('message')
def handle_message(data):
    user_message = data['message']
    resposta = gerar_resposta(user_message)
    emit('response', {'message': resposta})

if __name__ == '__main__':
    socketio.run(app, debug=True)
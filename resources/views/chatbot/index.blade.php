@extends('layouts.app')

@section('title', 'Assistant IA — TopoSmart')
@section('page-title', 'Assistant topographique IA')

@push('styles')
<style>
.chat-container {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 190px);
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
}
.chat-header {
    padding: 16px 20px;
    background: rgba(255,255,255,0.03);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}
.bot-avatar {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, var(--blue-400), var(--blue-500));
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.bot-avatar svg { fill: white; width: 18px; height: 18px; }
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-track { background: transparent; }
.chat-messages::-webkit-scrollbar-thumb { background: var(--navy-600); border-radius: 2px; }

.msg {
    display: flex;
    gap: 10px;
    max-width: 82%;
    animation: fadeIn 0.3s ease;
}
.msg.user { align-self: flex-end; flex-direction: row-reverse; }
.msg-avatar {
    width: 32px; height: 32px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
    font-weight: 700;
    flex-shrink: 0;
}
.msg.bot  .msg-avatar { background: linear-gradient(135deg, var(--blue-400), var(--blue-500)); color: #fff; }
.msg.user .msg-avatar { background: rgba(255,255,255,0.12); color: var(--text-light); }
.msg-bubble {
    padding: 12px 16px;
    border-radius: 14px;
    font-size: 13.5px;
    line-height: 1.65;
    max-width: 100%;
}
.msg.bot .msg-bubble {
    background: rgba(255,255,255,0.06);
    border: 1px solid var(--border);
    border-top-left-radius: 4px;
    color: var(--text-light);
    white-space: pre-wrap;
}
.msg.user .msg-bubble {
    background: var(--blue-400);
    color: #fff;
    border-top-right-radius: 4px;
}

.suggestions {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    padding: 10px 16px 14px;
    flex-shrink: 0;
    border-top: 1px solid var(--border);
    background: rgba(255,255,255,0.02);
}
.suggestion-chip {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11.5px;
    background: rgba(37,99,235,0.1);
    border: 1px solid rgba(59,130,246,0.25);
    color: #60a5fa;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.suggestion-chip:hover { background: rgba(37,99,235,0.2); border-color: rgba(59,130,246,0.4); }

.chat-input-zone {
    padding: 14px 16px;
    border-top: 1px solid var(--border);
    display: flex;
    gap: 10px;
    align-items: flex-end;
    background: rgba(255,255,255,0.02);
    flex-shrink: 0;
}
.chat-input {
    flex: 1;
    background: rgba(255,255,255,0.06);
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 11px 16px;
    color: var(--white);
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    resize: none;
    min-height: 46px;
    max-height: 120px;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.chat-input:focus {
    outline: none;
    border-color: var(--blue-400);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
    background: rgba(37,99,235,0.06);
}
.chat-input::placeholder { color: rgba(255,255,255,0.2); }
.send-btn {
    width: 46px; height: 46px;
    background: var(--blue-400);
    border: none;
    border-radius: 10px;
    color: #fff;
    font-size: 17px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.send-btn:hover { background: var(--blue-300); transform: scale(1.05); box-shadow: 0 4px 14px rgba(37,99,235,0.4); }
.send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

.typing-indicator { display: flex; align-items: center; gap: 4px; padding: 4px 0; }
.typing-dot {
    width: 7px; height: 7px;
    background: #60a5fa;
    border-radius: 50%;
    animation: typing 1.4s infinite;
}
.typing-dot:nth-child(2) { animation-delay: 0.2s; }
.typing-dot:nth-child(3) { animation-delay: 0.4s; }

@keyframes typing {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
    30% { transform: translateY(-6px); opacity: 1; }
}
@keyframes fadeIn {
    from { opacity:0; transform: translateY(8px); }
    to   { opacity:1; transform: translateY(0); }
}

/* Sidebar panels */
.topic-cat {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 8px;
    margin-top: 4px;
}
</style>
@endpush

@section('content')
<div class="breadcrumb-topo">
    <a href="{{ route('dashboard') }}">Accueil</a>
    <span class="sep">/</span>
    <span>Assistant IA</span>
</div>

<div class="row g-4">
    {{-- CHAT --}}
    <div class="col-lg-8">
        <div class="chat-container">
            {{-- Header --}}
            <div class="chat-header">
                <div class="bot-avatar">
                    <svg viewBox="0 0 24 24"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7H3a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M7.5 13c-.83 0-1.5.67-1.5 1.5S6.67 16 7.5 16 9 15.33 9 14.5 8.33 13 7.5 13m9 0c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5M3 18h18v2H3v-2z"/></svg>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;color:var(--white);">TopoBot</div>
                    <div style="font-size:12px;color:var(--text-muted);display:flex;align-items:center;gap:5px;">
                        <span style="display:inline-block;width:7px;height:7px;background:#22c55e;border-radius:50%;"></span>
                        Assistant ANCFCC — En ligne
                    </div>
                </div>
                <div style="margin-left:auto;">
                    <button onclick="clearChat()" style="background:rgba(255,255,255,0.05);border:1px solid var(--border);color:var(--text-muted);padding:5px 12px;border-radius:7px;font-size:12px;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.color='var(--white)'" onmouseout="this.style.color='var(--text-muted)'">
                        <i class="bi bi-trash3"></i> Effacer
                    </button>
                </div>
            </div>

            {{-- Messages --}}
            <div class="chat-messages" id="chat-messages">
                <div class="msg bot">
                    <div class="msg-avatar">
                        <svg viewBox="0 0 24 24" fill="white" width="14" height="14"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7H3a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M7.5 13c-.83 0-1.5.67-1.5 1.5S6.67 16 7.5 16 9 15.33 9 14.5 8.33 13 7.5 13m9 0c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5M3 18h18v2H3v-2z"/></svg>
                    </div>
                    <div class="msg-bubble">
                        Bonjour ! Je suis <strong>TopoBot</strong>, votre assistant topographique ANCFCC.<br><br>
                        Je peux vous aider sur l'immatriculation fonciere, la copropriete, le morcellement, les documents requis, les calculs topographiques, et bien plus.<br><br>
                        Comment puis-je vous aider ?
                    </div>
                </div>
            </div>

            {{-- Suggestions rapides --}}
            <div class="suggestions" id="suggestion-bar">
                <span class="suggestion-chip" onclick="sendSuggestion(this)">Immatriculation fonciere</span>
                <span class="suggestion-chip" onclick="sendSuggestion(this)">Documents pour copropriete</span>
                <span class="suggestion-chip" onclick="sendSuggestion(this)">Comment calculer une surface ?</span>
                <span class="suggestion-chip" onclick="sendSuggestion(this)">Convertir m2 en hectares</span>
                <span class="suggestion-chip" onclick="sendSuggestion(this)">Qu'est-ce que la MAJ ?</span>
            </div>

            {{-- Input --}}
            <div class="chat-input-zone">
                <textarea
                    id="chat-input"
                    class="chat-input"
                    placeholder="Posez votre question topographique..."
                    rows="1"
                    id="chat-input-field"
                    onkeydown="handleKey(event)"></textarea>
                <button class="send-btn" onclick="envoyerMessage()" id="send-btn">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- PANEL LATERAL --}}
    <div class="col-lg-4">
        <div class="card-topo mb-4" style="border-left:3px solid var(--blue-400);">
            <h6 style="font-size:13px;font-weight:700;color:#60a5fa;margin-bottom:16px;display:flex;align-items:center;gap:7px;">
                <i class="bi bi-lightbulb-fill"></i> Sujets disponibles
            </h6>
            @php
            $categories = [
                'Dossiers ANCFCC' => [
                    'Immatriculation fonciere',
                    'Mise a jour (MAJ)',
                    'Copropriete',
                    'Morcellement',
                    'Lotissement',
                ],
                'Documents requis' => [
                    'PV de bornage',
                    'Plan de masse',
                    'Tableau des surfaces',
                    'Reglement de copropriete',
                ],
                'Calculs topo' => [
                    'Calcul de distance',
                    'Calcul de surface',
                    'Conversion d\'unites',
                ],
                'Procedures' => [
                    'Cadastre Maroc',
                    'Statuts dossier',
                    'ANCFCC',
                ],
            ];
            @endphp
            @foreach($categories as $cat => $items)
                <div style="margin-bottom:14px;">
                    <div class="topic-cat">{{ $cat }}</div>
                    <div style="display:flex;flex-wrap:wrap;gap:5px;">
                        @foreach($items as $item)
                            <span onclick="sendSuggestion(this)" class="suggestion-chip" style="font-size:11px;">
                                {{ $item }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card-topo">
            <h6 style="font-size:13px;font-weight:700;margin-bottom:10px;display:flex;align-items:center;gap:7px;">
                <i class="bi bi-info-circle" style="color:#60a5fa;"></i> Astuce
            </h6>
            <p style="font-size:12px;color:var(--text-muted);margin:0;line-height:1.75;">
                Utilisez des termes precis comme <em style="color:rgba(255,255,255,0.6);">immatriculation, bornage, PV, tableau, calcul, surface</em> pour obtenir les meilleures reponses.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const messagesEl = document.getElementById('chat-messages');
const userInitial = '{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}';

function scrollToBottom() {
    messagesEl.scrollTop = messagesEl.scrollHeight;
}

function appendMessage(text, role) {
    const div = document.createElement('div');
    div.className = 'msg ' + role;
    const avatarContent = role === 'bot'
        ? `<svg viewBox="0 0 24 24" fill="white" width="14" height="14"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7H3a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M7.5 13c-.83 0-1.5.67-1.5 1.5S6.67 16 7.5 16 9 15.33 9 14.5 8.33 13 7.5 13m9 0c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5M3 18h18v2H3v-2z"/></svg>`
        : userInitial;
    div.innerHTML = `
        <div class="msg-avatar">${avatarContent}</div>
        <div class="msg-bubble">${text.replace(/\n/g, '<br>')}</div>
    `;
    messagesEl.appendChild(div);
    scrollToBottom();
}

function showTyping() {
    const div = document.createElement('div');
    div.className = 'msg bot';
    div.id = 'typing-indicator';
    div.innerHTML = `
        <div class="msg-avatar">
            <svg viewBox="0 0 24 24" fill="white" width="14" height="14"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7H3a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M7.5 13c-.83 0-1.5.67-1.5 1.5S6.67 16 7.5 16 9 15.33 9 14.5 8.33 13 7.5 13m9 0c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5M3 18h18v2H3v-2z"/></svg>
        </div>
        <div class="msg-bubble" style="padding:14px 18px;">
            <div class="typing-indicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        </div>
    `;
    messagesEl.appendChild(div);
    scrollToBottom();
}

function removeTyping() {
    document.getElementById('typing-indicator')?.remove();
}

async function envoyerMessage(customMsg = null) {
    const input = document.getElementById('chat-input');
    const message = customMsg || input.value.trim();
    if (!message) return;

    appendMessage(message, 'user');
    if (!customMsg) input.value = '';

    const btn = document.getElementById('send-btn');
    btn.disabled = true;
    showTyping();

    try {
        const res = await fetch('{{ route("chatbot.repondre") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ message })
        });
        const data = await res.json();
        await new Promise(r => setTimeout(r, 500));
        removeTyping();
        appendMessage(data.reponse, 'bot');
    } catch (e) {
        removeTyping();
        appendMessage('Une erreur est survenue. Veuillez reessayer.', 'bot');
    } finally {
        btn.disabled = false;
        input.focus();
    }
}

function sendSuggestion(el) {
    envoyerMessage(el.textContent.trim());
}

function clearChat() {
    messagesEl.innerHTML = '';
    appendMessage('Conversation reinitalisee. Comment puis-je vous aider ?', 'bot');
}

function handleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        envoyerMessage();
    }
}
</script>
@endpush

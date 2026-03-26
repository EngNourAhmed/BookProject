<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Book ERA</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SaaS Design System -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- ملف CSS مخصص -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/330/330731.png" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .active-conv {
            background: rgba(255, 214, 10, 0.05) !important;
            border-right: 3px solid var(--accent-yellow) !important;
        }
        .hover-bg-white-5:hover {
            background: rgba(255, 255, 255, 0.03);
        }
        .message-bubble {
            max-width: 70%;
            padding: 0.8rem 1.2rem;
            border-radius: 1.2rem;
            font-size: 0.95rem;
            line-height: 1.5;
            position: relative;
        }
        .msg-sent {
            align-self: flex-end;
            background: var(--accent-yellow);
            color: var(--bg-navy);
            border-bottom-right-radius: 0.2rem;
            font-weight: 500;
        }
        .msg-received {
            align-self: flex-start;
            background: rgba(255, 255, 255, 0.08);
            color: white;
            border-bottom-left-radius: 0.2rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .messenger-wrapper {
            height: 100vh;
            display: flex;
            overflow: hidden;
        }
        .chat-main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background: var(--bg-navy);
        }
    </style>
</head>
<body>
    <!-- Mobile Toggle -->
    <button class="mobile-toggle" id="mobileToggle">
        <i class="bi bi-list"></i>
    </button>

    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main-content p-0" id="mainContent" style="height: 100vh;">
        <div class="row g-0 h-100">
            <!-- Inbox Sidebar -->
            <div class="{{ request()->has('support') ? 'd-none' : 'col-md-4 col-lg-3 d-flex' }} flex-column border-end border-white-5 bg-white-5" style="height: 100vh;">
                <div class="p-4 border-bottom border-white-5">
                    <h4 class="text-white fw-bold mb-3">Messages</h4>
                    <div class="search-box">
                        <input type="text" class="form-control bg-white-5 border-white-10 text-white rounded-pill px-3" placeholder="Search conversations...">
                    </div>
                </div>
                
                <div class="flex-grow-1 overflow-y-auto custom-scrollbar" id="conversationList">
                    @forelse($conversations as $conv)
                        @php $other = $conv->otherUser(); @endphp
                        <div class="conversation-item p-3 border-bottom border-white-5 cursor-pointer d-flex align-items-center gap-3 hover-bg-white-5 transition-all @if($activeConversationId == $conv->id) active-conv @endif" 
                             onclick="loadChat({{ $conv->id }}, this)" data-id="{{ $conv->id }}">
                            <div class="avatar-sm flex-shrink-0" style="width: 48px; height: 48px; background: rgba(255, 214, 10, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid var(--accent-yellow);">
                                <i class="bi bi-person fs-5 text-accent"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="text-white fw-bold mb-0 text-truncate">{{ $other->name }}</h6>
                                    <small class="text-white-50 x-small">{{ $conv->lastMessage ? $conv->lastMessage->created_at->diffForHumans() : '' }}</small>
                                </div>
                                <p class="text-white-50 small mb-0 text-truncate last-msg">
                                    {{ $conv->lastMessage ? $conv->lastMessage->content : 'No messages yet' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-white-50">
                            <p>No conversations found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Active Chat Area -->
            <div class="{{ request()->has('support') ? 'col-12' : 'col-md-8 col-lg-9' }} d-flex flex-column bg-navy" style="height: 100vh;">
                <div id="chatHeader" class="p-3 border-bottom border-white-5 d-none">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm" style="width: 40px; height: 40px; background: rgba(255, 214, 10, 0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 214, 10, 0.2);">
                                <i class="bi bi-person text-accent"></i>
                            </div>
                            <div>
                                <h6 class="text-white fw-bold mb-0" id="chatTitle">Select a chat</h6>
                                <small class="text-accent x-small">Active now</small>
                            </div>
                        </div>
                        <div class="d-none">
                            <!-- Removed call, video, info buttons per user request -->
                        </div>
                    </div>
                </div>

                <div class="flex-grow-1 overflow-y-auto p-4 custom-scrollbar d-flex flex-column gap-3" id="messageBox">
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-white-50 opacity-50" id="noChatSelected">
                        <i class="bi bi-chat-dots display-1 mb-4"></i>
                        <h5>Select a conversation to start chatting</h5>
                    </div>
                </div>

                <div id="messageFormArea" class="p-3 border-top border-white-5 d-none">
                    <form id="chatForm" class="d-flex gap-2">
                        <input type="text" id="messageInput" class="form-control bg-transparent border-white-10 text-white rounded-pill px-4" placeholder="Type a message..." autocomplete="off">
                        <button type="submit" class="btn btn-accent rounded-circle p-0 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                            <i class="bi bi-send-fill text-navy"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>

    <script>
        let activeConversationId = {{ $activeConversationId ?? 'null' }};
        let currentAuthId = {{ auth()->id() }};

        function loadChat(id, element) {
            activeConversationId = id;
            
            // UI feedback
            document.querySelectorAll('.conversation-item').forEach(el => el.classList.remove('active-conv'));
            if(element) element.classList.add('active-conv');

            // Show UI elements
            document.getElementById('chatHeader').classList.remove('d-none');
            document.getElementById('messageFormArea').classList.remove('d-none');
            document.getElementById('noChatSelected').classList.add('d-none');

            fetch(`/messages/${id}`)
                .then(res => res.json())
                .then(data => {
                    const conv = data.conversation;
                    const otherUser = conv.user_one_id === data.auth_id ? conv.user_two : conv.user_one;
                    
                    document.getElementById('chatTitle').innerText = otherUser.name;
                    
                    const box = document.getElementById('messageBox');
                    box.innerHTML = '';
                    
                    conv.messages.forEach(msg => {
                        appendMessage(msg, data.auth_id);
                    });
                    
                    scrollToBottom();
                });
        }

        function appendMessage(msg, authId) {
            const box = document.getElementById('messageBox');
            const div = document.createElement('div');
            const isMe = msg.sender_id === authId;
            
            div.className = `message-bubble ${isMe ? 'msg-sent' : 'msg-received'}`;
            div.innerText = msg.content;
            box.appendChild(div);
        }

        function scrollToBottom() {
            const box = document.getElementById('messageBox');
            box.scrollTop = box.scrollHeight;
        }

        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('messageInput');
            const content = input.value.trim();
            
            if(!content || !activeConversationId) return;

            fetch(`/messages/${activeConversationId}`)
                .then(res => res.json())
                .then(data => {
                    const otherUser = data.conversation.user_one_id === currentAuthId ? data.conversation.user_two : data.conversation.user_one;
                    
                    fetch('/messages', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            recipient_id: otherUser.id,
                            content: content
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            appendMessage(data.message, currentAuthId);
                            input.value = '';
                            scrollToBottom();
                            
                            // Update last message in sidebar
                            const lastMsgEl = document.querySelector(`.conversation-item[data-id="${activeConversationId}"] .last-msg`);
                            if(lastMsgEl) lastMsgEl.innerText = content;
                        }
                    });
                });
        });

        if(activeConversationId) {
            window.addEventListener('load', () => {
                 const element = document.querySelector(`.conversation-item[data-id="${activeConversationId}"]`);
                 loadChat(activeConversationId, element);
            });
        }
    </script>
</body>
</html>

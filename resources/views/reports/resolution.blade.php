<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Resolution Center - Book ERA</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SaaS Design System -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <link rel="icon" href="{{ asset('images/Q.png') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .resolution-wrapper {
            height: 100vh;
            display: flex;
            overflow: hidden;
            background: var(--bg-navy);
        }
        .report-list-panel {
            width: 320px;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.02);
        }
        .chat-panel {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
        .context-panel {
            width: 300px;
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.01);
            padding: 1.5rem;
        }
        .report-item {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            cursor: pointer;
            transition: all 0.2s;
        }
        .report-item:hover {
            background: rgba(255, 255, 255, 0.03);
        }
        .report-item.active {
            background: rgba(255, 214, 10, 0.05);
            border-right: 3px solid var(--accent-yellow);
        }
        .message-bubble {
            max-width: 80%;
            padding: 0.8rem 1.2rem;
            border-radius: 1.2rem;
            margin-bottom: 0.5rem;
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
        }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="main-content p-0" id="mainContent">
        <div class="resolution-wrapper">
            <!-- Left: Report List -->
            <div class="report-list-panel">
                <div class="p-3 border-bottom border-white-5">
                    <h5 class="text-white fw-bold mb-0">Active Reports</h5>
                </div>
                <div class="flex-grow-1 overflow-y-auto custom-scrollbar">
                    @forelse($reports as $report)
                        <div class="report-item {{ $activeReport && $activeReport->id == $report->id ? 'active' : '' }}" 
                             onclick="window.location.href='{{ route('reports.resolution', ['id' => $report->id]) }}'">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="badge bg-warning x-small">Reviewing</span>
                                <small class="text-white-50">{{ $report->created_at->diffForHumans() }}</small>
                            </div>
                            <h6 class="text-white mb-1 text-truncate">{{ $report->reported->name ?? 'Unknown Author' }}</h6>
                            <p class="text-white-50 small mb-0 text-truncate">{{ $report->reason }}</p>
                        </div>
                    @empty
                        <div class="p-4 text-center text-white-50 small">No reports to resolve</div>
                    @endforelse
                </div>
            </div>

            <!-- Middle: Chat Area -->
            <div class="chat-panel">
                @if($activeReport)
                    <div class="p-3 border-bottom border-white-5 bg-white-5 d-flex align-items-center gap-3">
                        <div class="avatar-sm" style="width: 40px; height: 40px; background: rgba(255, 214, 10, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid var(--accent-yellow);">
                            <i class="bi bi-person text-accent"></i>
                        </div>
                        <div>
                            <h6 class="text-white fw-bold mb-0">Chatting with {{ $activeReport->reported->name }}</h6>
                            <small class="text-accent x-small">Topic: Reported Article</small>
                        </div>
                    </div>

                    <div id="messageBox" class="flex-grow-1 p-4 overflow-y-auto d-flex flex-column gap-2 custom-scrollbar">
                        <!-- Messages loaded via JS -->
                    </div>

                    <div class="p-3 border-top border-white-5">
                        <form id="chatForm" class="d-flex gap-2">
                            <input type="text" id="messageInput" class="form-control bg-transparent border-white-10 text-white rounded-pill px-4" placeholder="Message the writer..." autocomplete="off">
                            <button type="submit" class="btn btn-accent rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-send-fill text-navy"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-white-50 opacity-50">
                        <i class="bi bi-chat-left-dots display-3 mb-4"></i>
                        <h5>Select a report to start resolution</h5>
                    </div>
                @endif
            </div>

            <!-- Right: Context Panel -->
            @if($activeReport)
            <div class="context-panel">
                <h6 class="text-accent fw-bold text-uppercase small mb-4">Report Details</h6>
                
                <div class="mb-4">
                    <label class="text-white-50 x-small text-uppercase fw-bold mb-1">Article</label>
                    <p class="text-white mb-0 fw-bold">{{ $activeReport->article->title ?? 'Deleted' }}</p>
                    @if($activeReport->article)
                        <a href="{{ route('articles.show', $activeReport->article->id) }}" class="text-accent small text-decoration-none">View Article <i class="bi bi-arrow-right"></i></a>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="text-white-50 x-small text-uppercase fw-bold mb-1">Author (Reported)</label>
                    <p class="text-white mb-0">{{ $activeReport->reported->name ?? 'Unknown' }}</p>
                    <small class="text-white-50">{{ $activeReport->reported->email ?? '' }}</small>
                </div>

                <div class="mb-4">
                    <label class="text-white-50 x-small text-uppercase fw-bold mb-1">Reporter</label>
                    <p class="text-white mb-0">{{ $activeReport->reporter->name ?? 'Unknown' }}</p>
                </div>

                <div class="mb-4">
                    <label class="text-white-50 x-small text-uppercase fw-bold mb-1">Reason</label>
                    <div class="px-3 py-2 bg-white-5 rounded border border-white-5 mb-2">
                        <p class="text-white small mb-0">{{ $activeReport->reason }}</p>
                    </div>
                    @if($activeReport->description)
                        <label class="text-white-50 x-small text-uppercase fw-bold mb-1">Detailed Description</label>
                        <div class="p-3 bg-white-5 rounded border border-white-5">
                            <p class="text-white small mb-0">{{ $activeReport->description }}</p>
                        </div>
                    @endif
                </div>

                <div class="mt-auto pt-4 border-top border-white-5">
                    <form action="{{ route('reports.update', $activeReport->id) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" name="status" value="resolved" class="btn btn-saas-primary w-100 mb-2">Mark Resolved</button>
                        <button type="submit" name="status" value="blocked" class="btn btn-outline-danger w-100">Block Content</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- JS REUSE FROM MESSAGES -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        @if($activeReport && $activeConversation)
            const conversationId = {{ $activeConversation->id }};
            const recipientId = {{ $activeReport->reported_id }};
            const currentAuthId = {{ auth()->id() }};

            function loadChat() {
                fetch(`/messages/${conversationId}`)
                    .then(res => res.json())
                    .then(data => {
                        const box = document.getElementById('messageBox');
                        box.innerHTML = '';
                        data.conversation.messages.forEach(msg => {
                            appendMessage(msg, currentAuthId);
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
                if(!content) return;

                fetch('/messages', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        recipient_id: recipientId,
                        content: content
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        appendMessage(data.message, currentAuthId);
                        input.value = '';
                        scrollToBottom();
                    }
                });
            });

            // Initial load
            loadChat();
            // Poll for new messages every 5 seconds
            setInterval(loadChat, 5000);
        @endif
    </script>
</body>
</html>

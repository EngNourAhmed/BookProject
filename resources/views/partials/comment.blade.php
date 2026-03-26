<div class="comment-item d-flex gap-3 mb-4" id="comment-{{ $comment->id }}">
    <div class="avatar-sm flex-shrink-0" style="width: 38px; height: 38px; background: rgba(255, 255, 255, 0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 214, 10, 0.2);">
        <i class="bi bi-person text-white-50"></i>
    </div>
    <div class="flex-grow-1">
        <div class="comment-bubble p-3 rounded-4 bg-white-5 border border-white-5 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-accent fw-bold small">{{ $comment->user->name }}</span>
                <div class="d-flex align-items-center gap-2">
                    @if(auth()->id() === $comment->user_id)
                    <button class="btn btn-link text-white-50 p-0 fs-6 edit-comment-btn" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    @endif
                    @if(auth()->user()->role === 'admin' || auth()->id() === $comment->user_id)
                    <button class="btn btn-link text-danger p-0 border-0 opacity-60 hover-opacity-100 delete-comment-btn" data-id="{{ $comment->id }}" title="Delete">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                    @endif
                </div>
            </div>
            <div class="comment-body">
                <p class="text-white-80 mb-0 small comment-text" style="line-height: 1.6;" dir="auto">{{ $comment->content }}</p>
                @if($comment->user_id === auth()->id())
                <div class="edit-wrapper d-none mt-2">
                    <textarea class="form-control form-control-sm bg-white-5 border-white-10 text-white edit-content" rows="2">{{ $comment->content }}</textarea>
                    <div class="d-flex gap-2 mt-2">
                        <button class="btn btn-xs btn-accent save-edit-btn" data-id="{{ $comment->id }}" style="font-size: 0.7rem; padding: 2px 8px;">Save</button>
                        <button class="btn btn-xs btn-outline-white cancel-edit-btn" style="font-size: 0.7rem; padding: 2px 8px;">Cancel</button>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <div class="d-flex align-items-center gap-3 ms-2 mt-2">
            <small class="text-white-50 x-small">{{ $comment->created_at->diffForHumans() }}</small>
            
            <button class="btn btn-link p-0 text-white-50 x-small text-decoration-none hover-accent reply-toggle-btn" data-id="{{ $comment->id }}">
                Reply
                @if($comment->replies->count() > 0)
                <span class="ms-1 opacity-50">({{ $comment->replies->count() }})</span>
                @endif
            </button>
            
            <button class="btn btn-link p-0 text-white-50 x-small text-decoration-none hover-accent like-comment-btn" data-id="{{ $comment->id }}">
                <i class="bi {{ $comment->isLikedBy(auth()->user()) ? 'bi-heart-fill text-accent' : 'bi-heart' }} me-1"></i>
                <span class="likes-count">{{ $comment->likes->count() }}</span>
            </button>
        </div>

        <!-- Reply Input (Hidden) -->
        <div class="reply-input-wrapper mt-3 d-none" id="reply-form-{{ $comment->id }}">
            <div class="d-flex gap-2 p-2 rounded-3 bg-white-5 border border-white-10">
                <input type="text" class="form-control form-control-sm bg-transparent border-0 text-white reply-content submit-on-enter" placeholder="Write a reply..." style="box-shadow: none;">
                <button class="btn btn-sm btn-accent px-3 py-1 rounded-2 submit-reply-btn" data-parent-id="{{ $comment->id }}">Post</button>
            </div>
        </div>

        <!-- Nested Replies -->
        @if($comment->replies->count() > 0)
        <div class="replies-container ms-4 ps-3 border-start border-white-10 mt-3">
            @foreach($comment->replies as $reply)
                @include('partials.comment', ['comment' => $reply])
            @endforeach
        </div>
        @endif
    </div>
</div>

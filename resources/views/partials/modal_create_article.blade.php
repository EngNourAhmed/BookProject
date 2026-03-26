<div class="modal fade border-0" id="createArticleModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content overflow-hidden border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #0d1e36 0%, #020710 100%); padding: 2rem;">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center">
                    <div class="icon-box me-3" style="background: rgba(255, 214, 10, 0.1); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-file-earmark-plus" style="color: #ffd60a;"></i>
                    </div>
                    Create New Article
                </h5>
                <button type="button" class="btn-close btn-close-white opacity-50" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('articles.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-4" style="background: #020710;">
                    <div class="mb-4">
                        <label class="form-label text-white-50 fw-semibold x-small mb-2 text-uppercase letter-spacing-1">Article Title</label>
                        <input type="text" name="title" class="form-control" 
                            style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); color: white; padding: 12px 15px; border-radius: 12px;"
                            placeholder="Enter a catchy title..." required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-white-50 fw-semibold x-small mb-2 text-uppercase letter-spacing-1">Article Content</label>
                        <textarea name="content" class="form-control" rows="12" 
                            style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); color: white; padding: 15px; border-radius: 12px;"
                            placeholder="Write your masterpiece here..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4" style="background: #020710;">
                    <button type="button" class="btn btn-link text-white-50 text-decoration-none fw-semibold me-auto" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="action" value="draft" class="btn px-4 fw-bold text-white border border-white-5 border-opacity-25 me-2" 
                        style="background: rgba(255,255,255,0.05); border-radius: 12px; height: 48px; transition: all 0.3s ease;">
                        Save as Draft
                    </button>
                    <button type="submit" name="action" value="publish" class="btn px-4 fw-bold" 
                        style="background: #ffd60a; color: #020710; border-radius: 12px; height: 48px; min-width: 160px; transition: all 0.3s ease;">
                        Publish Article
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

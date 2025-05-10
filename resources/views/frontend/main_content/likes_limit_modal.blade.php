<!-- Modal -->
<div class="modal fade" id="likesLimitModal" tabindex="-1" aria-labelledby="likesLimitModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="likesLimitModalLabel">{{ get_phrase('Likes Required') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <i class="bi bi-heart-fill text-danger" style="font-size: 48px;"></i>
        </div>
        <p class="text-center">{{ get_phrase('You need to give at least 3 likes to other posts before posting more.') }}</p>
        <div class="d-flex justify-content-center align-items-center mt-3">
          <div class="likes-info">
            <span class="likes-count">0</span> / 3 {{ get_phrase('likes given') }}
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ get_phrase('Close') }}</button>
        <button class="btn btn-primary btn-show-gift-modal">{{ get_phrase('Browse Posts') }}</button>
      </div>
    </div>
  </div>
</div>
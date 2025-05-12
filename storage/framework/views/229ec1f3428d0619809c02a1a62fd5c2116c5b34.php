<!-- Modal -->
<div class="modal fade" id="likesLimitModal" tabindex="-1" aria-labelledby="likesLimitModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="likesLimitModalLabel"><?php echo e(get_phrase('Likes Required')); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <i class="bi bi-heart-fill text-danger" style="font-size: 48px;"></i>
        </div>
        <p class="text-center"><?php echo e(get_phrase('You need to give at least 3 likes to other posts before posting more.')); ?></p>
        <div class="d-flex justify-content-center align-items-center mt-3">
          <div class="likes-info">
            <span class="likes-count">0</span> / 3 <?php echo e(get_phrase('likes given')); ?>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(get_phrase('Close')); ?></button>
        <a href="<?php echo e(route('timeline')); ?>" class="btn btn-primary"><?php echo e(get_phrase('Browse Posts')); ?></a>
      </div>
    </div>
  </div>
</div> <?php /**PATH D:\OSPanel\home\boqiy-a.local\public\resources\views/frontend/main_content/likes_limit_modal.blade.php ENDPATH**/ ?>

<div wire:ignore.self class="modal fade show d-block" style="background-color: rgba(0, 0, 0, 0.5);" tabindex="-1" role="dialog" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg rounded">
                <div class="modal-body">
                    <div class="text-center">
                        <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Are you sure you want to delete this tax?</h5>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" wire:click="closeDeleteModal"  data-bs-dismiss="modal">Close</button>
                    <button type="button" wire:click="delete" class="btn btn-danger">Yes! Delete</button>
                </div>
        </div>
    </div>
</div>

<div class="modal fade show d-block" style="background-color: rgba(0, 0, 0, 0.5);" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $tax_id ? 'Edit Tax' : 'Create Tax' }}</h5>
                <button type="button" class="close btn" wire:click="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="taxname">Tax Name</label>
                        <input type="text" class="form-control" id="taxname" wire:model="taxname">
                        @error('taxname') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="percentage">Percentage</label>
                        <input type="text" class="form-control" id="percentage" wire:model="percentage">
                        @error('percentage') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary">Close</button>
                <button type="button" wire:click="store" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>


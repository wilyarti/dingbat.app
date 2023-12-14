<!-- Include stylesheet -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<!-- Create the editor container -->
<!-- Quill text editor -->
<div class="mt-2 bg-white" wire:ignore>
    <label>Description</label>
    <div
        x-data
        x-ref="quillEditor"
        name="planSettings.description"
        x-init="
                 quill = new Quill($refs.quillEditor, {theme: 'snow'});
                   quill.on('text-change', function () {
                     $dispatch('input', quill.root.innerHTML);
                   });

                 "
        wire:model.debounce.2000ms="planSettings.description"
    >
        {!! $planSettings['description'] !!}
    </div>
</div>


<!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<!-- Initialize Quill editor -->
<script>
    window.addEventListener('plan-updated', event => {
        var delta = event.detail.description;
        //quill.setContents(delta, 'silent');
        quill.root.innerHTML = delta
        //alert('Name updated to: ' + event.detail.description);
    })

</script>

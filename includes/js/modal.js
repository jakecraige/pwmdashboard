// JavaScript Document
$('#modal-from-dom').bind('show', function() {
    var id = $(this).data('id'),
        removeBtn = $(this).find('.danger'),
        href = removeBtn.attr('href');

    removeBtn.attr('href', href.replace(/ref=\d*/, 'ref=' + id));
})
.modal({ backdrop: true });

$('.confirm-delete').click(function(e) {
    e.preventDefault();
    
    var id = $(this).data('id');
    $('#modal-from-dom').data('id', id).modal('show');
});

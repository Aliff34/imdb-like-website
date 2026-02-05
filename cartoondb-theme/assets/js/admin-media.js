jQuery(function ($) {
  const galleryButton = $('#cartoondb_gallery_upload');
  if (!galleryButton.length) {
    return;
  }

  galleryButton.on('click', function (event) {
    event.preventDefault();

    const frame = wp.media({
      title: 'Select Gallery Images',
      button: {
        text: 'Use selected images'
      },
      multiple: true
    });

    frame.on('select', function () {
      const selection = frame.state().get('selection');
      const ids = selection.map(function (attachment) {
        return attachment.id;
      });
      $('#cartoondb_gallery_ids').val(ids.join(','));
    });

    frame.open();
  });
});

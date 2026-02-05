jQuery(function ($) {
  const galleryButton = $('#cartoondb_gallery_upload');
  const trailerButton = $('#cartoondb_trailer_upload');
  const castList = $('#cartoondb_cast_list');
  const castTemplate = $('#cartoondb_cast_template').html();

  if (galleryButton.length) {
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
  }

  if (trailerButton.length) {
    trailerButton.on('click', function (event) {
      event.preventDefault();

      const frame = wp.media({
        title: 'Select Trailer Video',
        button: {
          text: 'Use selected video'
        },
        library: {
          type: 'video'
        },
        multiple: false
      });

      frame.on('select', function () {
        const attachment = frame.state().get('selection').first();
        $('#cartoondb_trailer_video_id').val(attachment.id);
      });

      frame.open();
    });
  }

  function serializeCast() {
    const items = [];
    castList.find('.cartoondb-cast-item').each(function () {
      const item = $(this);
      items.push({
        name: item.find('.cartoondb-cast-name').val(),
        role: item.find('.cartoondb-cast-role').val(),
        image_id: item.find('.cartoondb-cast-image-id').val()
      });
    });
    $('#cartoondb_cast_json').val(JSON.stringify(items));
  }

  function bindCastItem(item) {
    item.find('.cartoondb-cast-remove').on('click', function () {
      item.remove();
      serializeCast();
    });

    item.find('.cartoondb-cast-upload').on('click', function (event) {
      event.preventDefault();
      const frame = wp.media({
        title: 'Select Cast Photo',
        button: {
          text: 'Use this image'
        },
        multiple: false
      });

      frame.on('select', function () {
        const attachment = frame.state().get('selection').first();
        item.find('.cartoondb-cast-image-id').val(attachment.id);
        item.find('.cartoondb-cast-preview').attr('src', attachment.attributes.url).show();
        serializeCast();
      });

      frame.open();
    });

    item.find('input').on('input', serializeCast);
  }

  if (castList.length) {
    castList.find('.cartoondb-cast-item').each(function () {
      bindCastItem($(this));
    });

    $('#cartoondb_cast_add').on('click', function (event) {
      event.preventDefault();
      const newItem = $(castTemplate);
      castList.append(newItem);
      bindCastItem(newItem);
      serializeCast();
    });

    serializeCast();
  }
});

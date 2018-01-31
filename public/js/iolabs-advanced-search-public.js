(function ($) {
  'use strict';

  $(document).on('select2:select', '.iosearch__select2', function(e) {
    var parent = $(this).parent('.iosearch__select');
    getDepth(e);
  });

  $('.iosearch__select2').select2({
    minimumResultsForSearch: -1,
    placeholder: "Select a region"
  });

  $('.iosearch__select2').each(function(i, elem) {
    var Text = $(elem).attr('data-text');
    $(elem).select2({
      minimumResultsForSearch: -1,
      placeholder: Text
    });
  });


  function getDepth(elem) {
    var id = elem.params.data.id;
    var type = $(elem.currentTarget).data('type');
    var depth = $(elem.currentTarget).data('depth');
    var parent = $(elem.currentTarget).parent('.iosearch__select');
    var text = $(elem.currentTarget).data('text');

    var newDepth = depth + 1;
    var newClass = type + '__level-' + newDepth;

    var maxDepth = $(parent).find('select').length;
    if(maxDepth > depth) {
      while(depth !== maxDepth) {
        depth = depth + 1;
        $(parent).find("[data-depth='"+depth+"']").select2('destroy').remove();
      }
    }

    var postData = {
      action: 'io_get_subcategories',
      category: id,
      type: type
    };

    $(".iosearch").addClass('loading');

    $.post(iodata.ajaxurl, postData, function (response) {
      if (Object.keys(response).length) {
        $(parent)
          .find('.select2').last()
          .after('<select class="iosearch__select2" id="'+ newClass +'" data-depth="'+ newDepth +'" data-text="'+ text +'" data-type="'+ type +'" name="'+ newClass +'"></select>');

        var newElem = $('#'+newClass);

        newElem.append('<option></option>');

        $.each(response, function (key, val) {
          newElem.append(
            '<option value="' + val.term_id + '">' + val.name + '</option>'
          );
        });

        newElem.select2({
          minimumResultsForSearch: -1,
          placeholder: text
        });
      }

    }, 'json')
      .always(function() {
        $(".iosearch").removeClass('loading');
      });
  }

  $(document).on('click', '.iosearch__reset', function() {
    var selectInstances = $("#iosearch__form").find('select');
    $("#iosearch__form").find('input').not('input[type=submit]').val('');
    selectInstances.each(function(i, obj) {
      if($(obj).data('depth') > 1) {
        $(obj).select2('destroy').remove();
      }
      $(obj).val('').trigger('change');
    });
  });

})(jQuery);

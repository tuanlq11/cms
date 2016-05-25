<footer>
	<!-- Add Footer Element In Here -->
</footer>
<script src="{{ asset('/build/app/js/app.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.scrollbar-macosx').scrollbar();

		if(localStorage.getItem('minimize') == 'active') {
			// $('#menu li.active').has('ul').children('ul').addClass('in');
			$('#column-left').addClass('minimize');
			$('#left-menu li').has('ul').children('ul').attr('aria-expanded','true');
			$('#left-menu li').children('ul').removeClass('collapse');
			$('#left-menu > li > a.parent').attr('aria-expanded','').attr('data-toggle','');
			$('#column-left div:first').removeClass('scroll-wrapper');
			$('#collapse-menu').children('i').replaceWith('<i class="fa fa-indent fa-fw fa-lg"></i>');
		}else{
			// $('#menu li.active').has('ul').children('ul').addClass('in');
			$('#collapse-menu').children('i').replaceWith('<i class="fa fa-dedent fa-fw fa-lg"></i>');
			$('#column-left').removeClass('minimize');
			$('#left-menu li').has('ul').children('ul').addClass('collapse');
		}
	});

    if (window.location.href ) {
        $('#left-menu a[href=\'' + window.location.href  + '\']').parents('li').addClass('active');
    } else {
        $('#left-menu').find('li:first-child').addClass('active');
    }

    $('#left-menu').children('li.active').has('ul').children('ul').addClass('in');

    $('#collapse-menu').on('click',function(){
        if($('#column-left').hasClass('minimize'))
        {
            localStorage.setItem('minimize','');
            $('#collapse-menu i').replaceWith('<i class="fa fa-dedent fa-fw fa-lg"></i>');
            $('#left-menu > li > ul').addClass('collapse');
            $('#column-left').removeClass('minimize');
            $('#column-left > div').addClass('scroll-wrapper');
            $('#left-menu > li > a.parent').attr('aria-expanded','false').attr('data-toggle','collapse');
        }else{
            localStorage.setItem('minimize','active');
            $('#collapse-menu i').replaceWith('<i class="fa fa-indent fa-fw fa-lg"></i>');
            $('#column-left').addClass('minimize');
            $('#column-left > div ').removeClass('scroll-wrapper');
            $('#left-menu > li > ul').removeClass('collapse').attr('aria-expanded','').css('height','auto');
            $('#left-menu > li > a.parent').attr('aria-expanded','').attr('data-toggle','');
        }
    });

    $('iframe').on('load', function() {
        var iHeight = $(this).contents().find('#page-wrapper').height();
        $(this).css('height', iHeight + 'px');
    });
</script>

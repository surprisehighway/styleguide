(function($) {

    function toggleSource(e) {
        var $target = $(this).parent().next();
        $target.toggleClass('sg-hide');
        if($target.hasClass('sg-hide')){
            $(this).html('View Source');
        } else {
            $(this).html('Show Source');
        }
        return false;
    }

    $(document).ready(function() {

        var flash = !!( navigator.mimeTypes[ "application/x-shockwave-flash" ] || "ActiveXObject" in window );

        
        $('.sg-source').addClass('sg-hide');

        $('.sg-demo').each(function(){
            var $this = $(this),
                viewBtn = $('<a />', {
                    class: 'sg-source-btn',
                    href: '#',
                    text: 'View Source'
                });

            $this.append(viewBtn);

            $this.on('click', '.sg-source-btn', toggleSource);

        });

    });

})(jQuery);
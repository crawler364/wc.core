(() => {
    window.WC = window.WC || {};
    WC.Ajax = WC.Ajax || {};

    WC.Ajax.Component = class {
        static load(data) {
            return $.ajax({
                url: '/ajax/component.php',
                data: data,
                method: 'POST',
                dataType: 'html',
                processData: true,
                async: true,
            });
        }
    }
})();

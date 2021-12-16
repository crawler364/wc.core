(() => {
    window.WC = window.WC || {};
    WC.Ajax = WC.Ajax || {};

    WC.Ajax.Component = class {
        static load(data) {
            let urlParams = new URLSearchParams(window.location.search);

            return $.ajax({
                url: `/ajax/component.php?${urlParams.toString()}`,
                data: data,
                method: 'POST',
                dataType: 'html',
                processData: true,
                async: true,
            });
        }
    }
})();

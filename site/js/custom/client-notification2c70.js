(function ($) {
    "use strict";
    $(document).ready(function () {
        JsManager.JqBootstrapValidation('#form-send-notification', (form, event) => {
            event.preventDefault();
            ClientQuery.SendNotification(form);

        });
        ClientQuery.GoogleMap();
    })

    var ClientQuery = {
        // Initialize and add the map
        GoogleMap: function () {
            // The location of Uluru
            const uluru = { lat: parseFloat($("#maplat").val()), lng: parseFloat($("#maplong").val()) };
            // The map, centered at Uluru
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 4,
                center: uluru,
            });
            // The marker, positioned at Uluru
            const marker = new google.maps.Marker({
                position: uluru,
                map: map,
            });
        },

        SendNotification: function (form) {
            if (Message.Prompt("Do You want to send your query?")) {
                JsManager.StartProcessBar();
                var jsonParam = form.serialize();
                var serviceUrl = "site-send-client-notification";
                JsManager.SendJson("POST", serviceUrl, jsonParam, onSuccess, onFailed);

                function onSuccess(jsonData) {
                    if (jsonData.status == "1") {
                        Message.Success("Successfully send email");
                    } else {
                        Message.Error("failed to send email");
                    }
                    JsManager.EndProcessBar();
                }

                function onFailed(xhr, status, err) {
                    JsManager.EndProcessBar();
                    Message.Exception(xhr);
                }
            }
        }
    }
})(jQuery);
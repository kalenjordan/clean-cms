define(['frontend/util', 'lib/jquery', '/js/lib/jquery.dnd_page_scroll.js'], function(util, $, dragScroll) {
    return {
        lastEnteredElementId: null,

        run: function() {
            var self = this;
            $().dndPageScroll();

            $('.cleancms-draggable').attr('draggable', true);
            $('.cleancms-draggable').each(function() {
                self.removeFieldsetHeader(this);
            })

            this.bindDragEvents();
        },

        bindDragEvents: function() {
            var self = this;
            var fieldsets = document.querySelectorAll('.cleancms-draggable');

            [].forEach.call(fieldsets, function(fieldset) {
                fieldset.addEventListener('dragstart', self.handleDragStart, false);
                fieldset.addEventListener('dragenter', self.handleDragEnter.bind(self), false);
                fieldset.addEventListener('dragend', self.handleDragEnd.bind(self), false);
                fieldset.addEventListener('dragover', self.handleDragOver, false);
                fieldset.addEventListener('drop', self.handleDrop.bind(self), false);
            });
        },

        // Prevent the default bounce back animation deal.
        handleDrop: function(e) {
            e.preventDefault();
        },

        // This is necessary in order for the preventDefault to work in the handleDrop
        handleDragOver: function(e) {
            if (e.preventDefault) {
                e.preventDefault(); // Necessary. Allows us to drop.
            }

            e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

            return false;
        },

        handleDragEnd: function(e) {
            if (this.lastEnteredElementId && this.lastEnteredElementId != e.target.id) {
                e.preventDefault();
                this.relocateFieldset(e.target.id);
                this.regenerateSortOrders();
                util.log('Going to insert ' + e.target.id + ' after ' + this.lastEnteredElementId);
            }
        },

        relocateFieldset: function(fieldsetId)
        {
            var html = $('#' + fieldsetId)[0].outerHTML;
            $('#' + fieldsetId).remove();
            $('#' + this.lastEnteredElementId).after(html);
            this.bindDragEvents();

            return this;
        },

        regenerateSortOrders: function()
        {
            var sortOrder = 10;
            $('.cleancms-draggable').each(function() {
                $(this).find('.sort-order').val(sortOrder);
                sortOrder += 10;
            });
        },

        handleDragStart: function(e) {
            util.log('Starting to drag ' + e.target.id);
        },

        handleDragEnter: function(e) {
            if (typeof(e.currentTarget.classList[1]) != 'undefined') {
                if (e.currentTarget.classList[1] == 'cleancms-draggable') {
                    if (e.target.id != e.currentTarget.id) {
                        this.lastEnteredElementId = e.currentTarget.id;
                        util.log("Entered draggable element: " + this.lastEnteredElementId);
                    }
                }
            }
        },

        removeFieldsetHeader: function(fieldsetElement) {
            var headerText = $(fieldsetElement).prev().text();

            $(fieldsetElement).prepend("<span class='fieldset-name'>" + headerText + "</span>");
            $(fieldsetElement).prev().hide();
        },
    }
});
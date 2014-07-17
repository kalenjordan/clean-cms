define(['frontend/util', 'lib/jquery', '/js/lib/jquery.dnd_page_scroll.js'], function(util, $, dragScroll) {
    return {
        lastEnteredElementId: null,
        currentlyDraggingElementId: null,

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
                fieldset.addEventListener('dragstart',  self.handleDragStart.bind(self), false);
                fieldset.addEventListener('dragenter',  self.handleDragEnter.bind(self), false);
                fieldset.addEventListener('dragend',    self.handleDragEnd.bind(self), false);
                fieldset.addEventListener('dragover',   self.handleDragOver.bind(self), false);
                fieldset.addEventListener('drop',       self.handleDrop.bind(self), false);
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
            if (this.lastEnteredElementId && this.lastEnteredElementId != this._getDraggingElementId(e)) {
                e.preventDefault();
                this.relocateFieldset(this._getDraggingElementId(e));
                this.regenerateSortOrders();
                this.lastEnteredElementId = null;
                $('.cleancms-draggable').removeClass("dragged-over");
            }
        },

        _getDraggingElementId: function(e) {
            return this.currentlyDraggingElementId;
        },

        relocateFieldset: function(fieldsetId)
        {
            var html = $('#' + fieldsetId)[0].outerHTML;
            var formValues = $('#' + fieldsetId).find('input, textarea');

            $('#' + fieldsetId).remove();
            $('#' + this.lastEnteredElementId).after(html);
            $('#' + fieldsetId).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);

            formValues.each(function() {
                var fieldId = $(this).attr('id');
                $('#' + fieldId).val($(this).val());
                console.log($(this).attr('id'));
            })

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
            this.currentlyDraggingElementId = e.target.id;
        },

        _getDraggableElementId: function(e) {
            if (typeof(e.currentTarget.classList[1]) == 'undefined') {
                return null;
            }

            if (e.currentTarget.classList[1] != 'cleancms-draggable') {
                return null;
            }

            return e.currentTarget.id;
        },

        handleDragEnter: function(e) {
            var elementId = this._getDraggableElementId(e);
            if (elementId) {
                if (this.lastEnteredElementId != e.currentTarget.id) {
                    this._enteredNewDraggable(e);
                }

                this.lastEnteredElementId = e.currentTarget.id;
            }
        },

        _enteredNewDraggable: function(e)
        {
            var elementId = this._getDraggableElementId(e);
            $('.cleancms-draggable').removeClass("dragged-over");
            $('#' + elementId).addClass("dragged-over");
            
            return this;
        },

        removeFieldsetHeader: function(fieldsetElement) {
            var headerText = $(fieldsetElement).prev().text();

            $(fieldsetElement).prepend("<span class='fieldset-name'>" + headerText + "</span>");
            $(fieldsetElement).prev().hide();
        },
    }
});
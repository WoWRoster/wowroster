/*
 * jQuery UI selectmenu version 1.1.0
 *
 * @version    SVN: $Id: $
 *
 * http://dochoffiday.com/Professional/jquery-ui-checkbox-radiobutton-replacement
 */ (function ($, undefined) {

    var lastActive, baseClasses = "ui-button ui-widget ui-state-default ui-corner-all",
        stateClasses = "ui-state-hover ui-state-active ",
        typeClasses = "ui-button-icons-only ui-button-icon-only ui-button-text-icons ui-button-text-icon-primary ui-button-text-only",
        formResetHandler = function (event) {
            $(":ui-button", event.target.form).each(function () {
                var inst = $(this).data("button");
                setTimeout(function () {
                    inst.refresh();
                }, 1);
            });
        },
        radioGroup = function (radio) {
            var name = radio.name,
                form = radio.form,
                radios = $([]);
            if (name) {
                if (form) {
                    radios = $(form).find("[name='" + name + "']");
                } else {
                    radios = $("[name='" + name + "']", radio.ownerDocument).filter(function () {
                        return !this.form;
                    });
                }
            }
            return radios;
        };

    $.widget("ui.checkbox", {
        options: {
            disabled: null,
            text: true,
            label: null,
            icons: {
                on: 'ui-icon-circle-check',
                off: 'ui-icon-radio-on'
            }
        },
        _create: function () {
            this.element.closest("form").unbind("reset.button").bind("reset.button", formResetHandler);

            if (typeof this.options.disabled !== "boolean") {
                this.options.disabled = this.element.attr("disabled");
            }

            this._determineButtonType();
            this.hasTitle = !! this.buttonElement.attr("title");

            var self = this,
                options = this.options,
                toggleButton = this.type === "checkbox" || this.type === "radio",
                hoverClass = "ui-state-hover" + (!toggleButton ? " ui-state-active" : ""),
                focusClass = "ui-state-focus";

            if (options.label === null) {
                options.label = this.buttonElement.html();
            }

            if (this.element.is(":disabled")) {
                options.disabled = true;
            }

            this.buttonElement.addClass(baseClasses).attr("role", "button").bind("mouseenter.button", function () {
                if (options.disabled) {
                    return;
                }
                $(this).addClass("ui-state-hover");
                if (this === lastActive) {
                    $(this).addClass("ui-state-active");
                }
            }).bind("mouseleave.button", function () {
                if (options.disabled) {
                    return;
                }
                $(this).removeClass(hoverClass);
            }).bind("focus.button", function () {
                // no need to check disabled, focus won't be triggered anyway
                $(this).addClass(focusClass);
            }).bind("blur.button", function () {
                $(this).removeClass(focusClass);
            });

            if (toggleButton) {
                this.element.bind("change.button", function () {
                    self.refresh();
                });
            }

            if (this.type === "checkbox") {
                this.buttonElement.bind("click.button", function () {
                    if (options.disabled) {
                        return false;
                    }
                    $(this).toggleClass("ui-state-active");
                    self.buttonElement.attr("aria-pressed", self.element[0].checked);
                });
            } else if (this.type === "radio") {
                this.buttonElement.bind("click.button", function () {
                    if (options.disabled) {
                        return false;
                    }
                    $(this).addClass("ui-state-active");
                    self.buttonElement.attr("aria-pressed", true);

                    var radio = self.element[0];
                    radioGroup(radio).not(radio).map(function () {
                        return $(this).button("widget")[0];
                    }).removeClass("ui-state-active").attr("aria-pressed", false);
                });
            } else {
                this.buttonElement.bind("mousedown.button", function () {
                    if (options.disabled) {
                        return false;
                    }
                    $(this).addClass("ui-state-active");
                    lastActive = this;
                    $(document).one("mouseup", function () {
                        lastActive = null;
                    });
                }).bind("mouseup.button", function () {
                    if (options.disabled) {
                        return false;
                    }
                    $(this).removeClass("ui-state-active");
                }).bind("keydown.button", function (event) {
                    if (options.disabled) {
                        return false;
                    }
                    if (event.keyCode == $.ui.keyCode.SPACE || event.keyCode == $.ui.keyCode.ENTER) {
                        $(this).addClass("ui-state-active");
                    }
                }).bind("keyup.button", function () {
                    $(this).removeClass("ui-state-active");
                });

                if (this.buttonElement.is("a")) {
                    this.buttonElement.keyup(function (event) {
                        if (event.keyCode === $.ui.keyCode.SPACE) {
                            // TODO pass through original event correctly (just as 2nd argument doesn't work)
                            $(this).click();
                        }
                    });
                }
            }

            // TODO: pull out $.Widget's handling for the disabled option into
            // $.Widget.prototype._setOptionDisabled so it's easy to proxy and can
            // be overridden by individual plugins
            this._setOption("disabled", options.disabled);
        },

        _determineButtonType: function () {

            if (this.element.is(":checkbox")) {
                this.type = "checkbox";
            } else {
                if (this.element.is(":radio")) {
                    this.type = "radio";
                } else {
                    if (this.element.is("input")) {
                        this.type = "input";
                    } else {
                        this.type = "button";
                    }
                }
            }

            if (this.type === "checkbox" || this.type === "radio") {
                // we don't search against the document in case the element
                // is disconnected from the DOM
                this.buttonElement = this.element.parents().last().find("label[for=" + this.element.attr("id") + "]");
                this.element.addClass("ui-helper-hidden-accessible");

                var checked = this.element.is(":checked");
                if (checked) {
                    this.buttonElement.addClass("ui-state-active");
                }
                this.buttonElement.attr("aria-pressed", checked);
            } else {
                this.buttonElement = this.element;
            }
        },

        widget: function () {
            return this.buttonElement;
        },

        destroy: function () {
            this.element.removeClass("ui-helper-hidden-accessible");
            this.buttonElement.removeClass(baseClasses + " " + stateClasses + " " + typeClasses).removeAttr("role").removeAttr("aria-pressed").html(this.buttonElement.find(".ui-button-text").html());

            if (!this.hasTitle) {
                this.buttonElement.removeAttr("title");
            }

            $.Widget.prototype.destroy.call(this);
        },

        _setOption: function (key, value) {
            $.Widget.prototype._setOption.apply(this, arguments);
            if (key === "disabled") {
                if (value) {
                    this.element.attr("disabled", true);
                } else {
                    this.element.removeAttr("disabled");
                }
            }
            this._resetButton();
        },

        refresh: function () {
            var isDisabled = this.element.is(":disabled");
            if (isDisabled !== this.options.disabled) {
                this._setOption("disabled", isDisabled);
            }
            if (this.type === "radio") {
                radioGroup(this.element[0]).each(function () {
                    if ($(this).is(":checked")) {
                        $(this).checkbox("widget").addClass("ui-state-active").attr("aria-pressed", true).find('.ui-button-icon-primary').addClass(this.options.icons.on).removeClass(this.options.icons.off);
                    } else {
                        $(this).checkbox("widget").removeClass("ui-state-active").attr("aria-pressed", false).find('.ui-button-icon-primary').removeClass(this.options.icons.off).addClass(this.options.icons.off);
                    }
                });
            } else if (this.type === "checkbox") {
                if (this.element.is(":checked")) {
                    this.buttonElement.addClass("ui-state-active").attr("aria-pressed", true).find('.ui-button-icon-primary').addClass(this.options.icons.on).removeClass(this.options.icons.off);
                } else {
                    this.buttonElement.removeClass("ui-state-active").attr("aria-pressed", false).find('.ui-button-icon-primary').removeClass(this.options.icons.on).addClass(this.options.icons.off);
                }
            }
        },

        _resetButton: function () {
            if (this.type === "input") {
                if (this.options.label) {
                    this.element.val(this.options.label);
                }
                return;
            }
            var checked = this.element.is(":checked");
            var buttonElement = this.buttonElement.removeClass(typeClasses),
                buttonText = $("<span></span>").addClass("ui-button-text").html(this.options.label).appendTo(buttonElement.empty()).text(),
                icons = this.options.icons;
            if (icons.on || icons.off) {
                buttonElement.addClass("ui-button-text-icon" + "-primary");
                buttonElement.prepend("<span class='ui-button-icon-primary ui-icon " + (checked ? icons.on : icons.off) + "'></span>");
                if (!this.options.text) {
                    buttonElement.addClass("ui-button-icon-only").removeClass("ui-button-text-icons ui-button-text-icon-primary");
                    if (!this.hasTitle) {
                        buttonElement.attr("title", buttonText);
                    }
                }
            } else {
                buttonElement.addClass("ui-button-text-only");
            }
        }
    });

})(jQuery);

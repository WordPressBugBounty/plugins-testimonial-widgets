String.prototype.ucfirst = function() {
	return this.charAt(0).toUpperCase() + this.slice(1)
};
jQuery(document).ready(function() {
	let nav = jQuery("#testimonial-widgets-plugin-settings-page .nav-tab-wrapper");
	if (nav.length) {
		let width = nav.find(".nav-tab-right").outerWidth();
		nav.css("padding-right", parseInt(width + 5) + "px")
	}
	jQuery(".ti-toggle-opacity").css("opacity", 1);

	jQuery("#testimonial-widgets-plugin-settings-page .btn-toggle").on("click", function(g) {
		g.preventDefault();
		jQuery(jQuery(this).attr("href")).toggle();
		return false
	});
	jQuery(".btn-copy2clipboard").click(function(g) {
		g.preventDefault();
		let obj = jQuery(jQuery(this).attr("href"));
		let text = obj.html() ? obj.html() : obj.val();
		TI_copyTextToClipboard(text)
	});
	var a = function() {
		let style_id = jQuery("#ti-style-id").val();
		let box = jQuery("#ti-review-list").closest(".ti-preview-box");
		if (["8", "9", "10", "11", "12", "20", "22"].indexOf(style_id) != -1 && !b) {
			box.css("width", "30%")
		} else {
			if (["6", "7", "24", "25", "26", "27", "28", "29", "35"].indexOf(style_id) != -1 && !b) {
				box.css("width", "50%")
			} else {
				box.css("width", "auto")
			}
		}
		box.css("width", box.width())
	};
	jQuery(".ti-checkbox:not(.disabled)").on("click", function() {
		let checkbox = jQuery(this).find("input[type=checkbox], input[type=radio]");
		checkbox.prop("checked", !checkbox.prop("checked")).trigger("change");
		return false
	});
	var b = false;
	var f = jQuery("#ti-review-list .ti-widget").clone();
	f.find(".ti-review-item").each(function() {
		let el = jQuery(this);
		let rating = el.find(".ti-stars .ti-star.f, .stars .ti-star.f").length;
		if (el.find(".ti-recommendation-icon.positive").length) {
			rating = 5
		} else {
			if (el.find(".ti-recommendation-icon.negative").length) {
				rating = 1
			}
		}
		if (el.find(".ti-polarity-icon.positive").length) {
			rating = 5
		} else {
			if (el.find(".ti-polarity-icon.neutral").length) {
				rating = 3
			} else {
				if (el.find(".ti-polarity-icon.negative").length) {
					rating = 1
				}
			}
		}
		if (el.find(".ti-rating-box").length) {
			rating = Math.round(parseFloat(el.find(".ti-rating-box").text()) / 2)
		}
		let selector = ".ti-review-content";
		if (el.find(".ti-review-content .ti-inner").length) {
			selector = ".ti-review-content .ti-inner"
		} else {
			if (el.find(".ti-review-text").length) {
				selector = ".ti-review-text"
			}
		}
		el.attr("data-rating", rating);
		el.attr("data-empty", el.find(selector).text().trim() == "" ? 1 : 0)
	});
	var e = function() {
		let platform = (jQuery("#ti-filter #show-star").data("platform") || "google").ucfirst();
		let el = jQuery('<div class="ti-widget" style="display: none"><div class="source-' + platform + '"><span class="ti-star f"></span><span class="ti-star e"></span></div></div>');
		el.append("body");
		jQuery("body").append(el);
		jQuery("#ti-filter .ti-star.e").css("background", el.find(".ti-star.e").css("background"));
		jQuery("#ti-filter .ti-star.f").css("background", el.find(".ti-star.f").css("background"));
		el.remove()
	};
	e();
	var d = function() {
		let layout_id = jQuery("#ti-review-list .ti-widget").data("layout-id");
		return [11, 12, 20, 22, 24, 25, 26, 27, 28, 29, 35].indexOf(layout_id) != -1
	};
	var c = function() {
		if (f.find(".ti-review-item").length == 0 && !d()) {
			jQuery("#ti-review-list").hide().next().fadeIn();
		}
	};
	if (f.length) {
		c();
		a()
	}
	jQuery("#ti-order-id, #ti-display-type-id, #ti-text-align-id, #ti-arrow-id, #ti-dots-id, #ti-dateformat-id, #ti-navigation-id, #ti-widget-options input[type=checkbox]:not(.no-form-update), #ti-box, #ti-font, #ti-slider-interval, #ti-review-lines")
	.on("change", function() {
		let form = jQuery(this).closest("form");
		let data = form.serializeArray();
		form.find("input[type=checkbox]:not(.no-form-update)").each(function() {
			let checkbox = jQuery(this);
			if (!checkbox.prop("checked") && checkbox.attr("name")) {
				data.push({
					name: checkbox.attr("name"),
					value: 0
				})
			}
		});
		jQuery("#ti-loading").addClass("active");

		jQuery("li.ti-preview-box").addClass("disabled");
		jQuery.ajax({
			url: form.attr("action"),
			type: "post",
			dataType: "application/json",
			data: data
		}).always(function() {
			location.reload(true)
		});
		return false
	});
	jQuery("input[name=layout-select]").on("change", function(g) {
		g.preventDefault();
		let ids = (jQuery("input[name=layout-select]:checked").data("ids") + "").split(",");
		if (ids == "") {
			jQuery(".ti-preview-boxes-container").find(".ti-full-width, .ti-half-width").fadeIn()
		} else {
			jQuery(".ti-preview-boxes-container").find(".ti-full-width, .ti-half-width").hide();
			ids.forEach(function(h) {
				jQuery(".ti-preview-boxes-container").find('.ti-preview-boxes[data-layout-id="' + h + '"]').parent().fadeIn()
			})
		}
		return false
	});
	let is_stepping = false;
	jQuery(".ti-free-steps li.done, .ti-free-steps li.active").on("click", function(g) {
		g.preventDefault();
		if (is_stepping) {
			return false
		}
		is_stepping = true;
		window.location.href = jQuery(this).attr("href");
		return false
	});
	if (jQuery(".ti-free-steps:not(.ti-setup-guide-steps) li.current").length == 0) {
		jQuery(".ti-free-steps:not(.ti-setup-guide-steps) li.active:last").addClass("current")
	}
	jQuery(document).on("click", ".btn-modal-close", function(g) {
		g.preventDefault();
		jQuery(this).closest(".ti-modal").fadeOut()
	});
	jQuery(document).on("click", ".ti-modal", function(g) {
		if (g.target.nodeName != "A") {
			g.preventDefault();
			if (!jQuery(g.target).closest(".ti-modal-dialog").length) {
				jQuery(this).fadeOut()
			}
		}
	});
	let highlight_modal = jQuery("#ti-highlight-modal");
	if (highlight_modal.length) {
		let appendHiddenInputs = function(g) {
			highlight_modal.find("input[type=hidden]").each(function() {
				let input = jQuery(this);
				g[input.attr("name")] = input.val()
			});
			return g
		};
		jQuery(document).on("click", ".btn-highlight", function(g) {
			g.preventDefault();
			let btn = jQuery(this);
			let review_box = btn.closest("tr").find(".ti-review-content");
			let raw_content = review_box.html();
			let content = raw_content.replace(/<mark class="ti-highlight">/g, "").replace(/<\/mark>/, "");
			highlight_modal.fadeIn();
			highlight_modal.find(".ti-highlight-content").html("<div class='raw-content'>" + raw_content + "</div><div class='selection-content'>" + content + "</div>");
			highlight_modal.find(".btn-highlight-confirm, .btn-highlight-remove").attr("href", btn.attr("href"));
			if (btn.hasClass("has-highlight")) {
				highlight_modal.find(".btn-highlight-remove").show()
			} else {
				highlight_modal.find(".btn-highlight-remove").hide()
			}
		});
		jQuery(document).on("click", ".btn-highlight-confirm", function(g) {
			g.preventDefault();
			let btn = jQuery(this);
			let highlight_content = btn.closest(".ti-modal-content").find(".ti-highlight-content .selection-content");
			let data = TI_highlight_getSelection(highlight_content.get(0));
			if (data.start !== null) {
				data.id = btn.attr("href");
				data["save-highlight"] = 1;
				btn.css("pointer-events", "none");
				btn.blur();
				btn.addClass("btn-disabled");
				TI_manage_dots(btn);
				btn.closest(".ti-modal").find(".btn-text").css("pointer-events", "none");
				jQuery.ajax({
					method: "POST",
					url: window.location.href,
					data: appendHiddenInputs(data)
				}).always(function() {
					location.reload(true)
				})
			}
		});
		jQuery(document).on("click", ".btn-highlight-remove", function(g) {
			g.preventDefault();
			let btn = jQuery(this);
			let highlight_content = btn.closest(".ti-modal-content").find(".ti-highlight-content");
			let data = TI_highlight_getSelection(highlight_content.get(0));
			btn.css("pointer-events", "none");
			btn.blur();
			btn.addClass("btn-disabled");
			TI_manage_dots(btn);
			btn.closest(".ti-modal").find(".btn-text").css("pointer-events", "none");
			jQuery.ajax({
				method: "POST",
				url: window.location.href,
				data: appendHiddenInputs({
					id: btn.attr("href"),
					"save-highlight": 1
				})
			}).always(function() {
				location.reload(true)
			})
		})
	}
	jQuery(document).on("click", "#ti-widget-nonce-notification .notice-dismiss", function() {
		let button = jQuery(this);
		if (!button.data("ajax-run")) {
			button.data("ajax-run", 1);
			jQuery.post("", {
				command: "save-widget-nonce-notice-hide"
			})
		}
	})

	jQuery(".ti-no-stars .ti-stars .ti-star").remove();
});

function TI_manage_dots(a) {
	let loading_text = a.data("loading-text");
	let num_of_dots = (a.html().match(new RegExp(/\./, "g")) || []).length;
	let next_dots = [".", "..", "...", ""];
	a.html(loading_text + next_dots[num_of_dots]);
	setTimeout(function() {
		TI_manage_dots(a)
	}, 1000)
}

function decodeHTMLEntities(a) {
	let textArea = document.createElement("textarea");
	textArea.innerHTML = a;
	return textArea.value
}

function TI_copyTextToClipboard(c) {
	c = decodeHTMLEntities(c);
	if (!navigator.clipboard) {
		var b = document.createElement("textarea");
		b.value = c;
		b.style.position = "fixed";
		document.body.appendChild(b);
		b.focus();
		b.select();
		try {
			var d = document.execCommand("copy")
		} catch (a) {}
		document.body.removeChild(b);
		return
	}
	navigator.clipboard.writeText(c).then(function() {}, function(e) {})
};
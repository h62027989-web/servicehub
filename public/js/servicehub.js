(function () {
  'use strict';

  var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var isTouchDevice = ('ontouchstart' in window) || navigator.maxTouchPoints > 0;
  var fineHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches && !isTouchDevice;

  /* ---------------------------------------------------------------------
   * Scroll progress bar + header shrink-on-scroll
   * ------------------------------------------------------------------- */
  var scrollProgress = document.getElementById('scrollProgress');
  var topbar = document.querySelector('.topbar');
  var scrollTicking = false;

  function onScrollFrame() {
    var doc = document.documentElement;
    var scrollTop = window.scrollY || doc.scrollTop || 0;

    if (scrollProgress) {
      var max = (doc.scrollHeight - doc.clientHeight) || 1;
      var pct = Math.min(100, Math.max(0, (scrollTop / max) * 100));
      scrollProgress.style.width = pct + '%';
    }
    if (topbar) {
      topbar.classList.toggle('scrolled', scrollTop > 10);
    }
    scrollTicking = false;
  }

  window.addEventListener('scroll', function () {
    if (!scrollTicking) {
      requestAnimationFrame(onScrollFrame);
      scrollTicking = true;
    }
  }, { passive: true });
  onScrollFrame();

  /* ---------------------------------------------------------------------
   * Button ripple + press feedback
   * ------------------------------------------------------------------- */
  var rippleSelector = '.btn,.primary,.small-btn,.danger,button.primary,.btn-secondary,.btn-outline,.btn-ghost,.btn-danger';
  document.addEventListener('pointerdown', function (e) {
    if (prefersReducedMotion) return;
    var target = e.target.closest ? e.target.closest(rippleSelector) : null;
    if (!target || target.disabled || target.classList.contains('btn-loading')) return;

    var rect = target.getBoundingClientRect();
    var size = Math.max(rect.width, rect.height) * 1.4;
    var x = (e.clientX || rect.left + rect.width / 2) - rect.left - size / 2;
    var y = (e.clientY || rect.top + rect.height / 2) - rect.top - size / 2;

    var ripple = document.createElement('span');
    ripple.className = 'ripple-el';
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    target.appendChild(ripple);
    setTimeout(function () { ripple.remove(); }, 650);
  });

  /* ---------------------------------------------------------------------
   * Subtle 3D tilt on service cards (desktop, fine pointer only)
   * ------------------------------------------------------------------- */
  if (fineHover && !prefersReducedMotion) {
    document.querySelectorAll('.service-card').forEach(function (card) {
      var frame = null;
      card.addEventListener('mousemove', function (e) {
        var rect = card.getBoundingClientRect();
        var px = (e.clientX - rect.left) / rect.width - 0.5;
        var py = (e.clientY - rect.top) / rect.height - 0.5;
        var maxTilt = 3;
        var rotateY = px * maxTilt * 2;
        var rotateX = -py * maxTilt * 2;

        if (frame) cancelAnimationFrame(frame);
        frame = requestAnimationFrame(function () {
          card.classList.add('tilting');
          card.style.transform =
            'translateY(-6px) scale(1.012) perspective(900px) rotateX(' + rotateX.toFixed(2) + 'deg) rotateY(' + rotateY.toFixed(2) + 'deg)';
        });
      });
      card.addEventListener('mouseleave', function () {
        if (frame) cancelAnimationFrame(frame);
        requestAnimationFrame(function () {
          card.style.transform = '';
          card.classList.remove('tilting');
        });
      });
    });
  }

  /* ---------------------------------------------------------------------
   * Hero mouse-move parallax
   * ------------------------------------------------------------------- */
  if (fineHover && !prefersReducedMotion) {
    document.querySelectorAll('.hero').forEach(function (hero) {
      var frame = null;
      hero.addEventListener('mousemove', function (e) {
        var rect = hero.getBoundingClientRect();
        var mx = (e.clientX - rect.left) / rect.width - 0.5;
        var my = (e.clientY - rect.top) / rect.height - 0.5;
        if (frame) cancelAnimationFrame(frame);
        frame = requestAnimationFrame(function () {
          hero.style.setProperty('--mx', mx.toFixed(3));
          hero.style.setProperty('--my', my.toFixed(3));
        });
      });
      hero.addEventListener('mouseleave', function () {
        hero.style.setProperty('--mx', 0);
        hero.style.setProperty('--my', 0);
      });
    });
  }

  /* ---------------------------------------------------------------------
   * Mobile sidebar toggle
   * ------------------------------------------------------------------- */
  var menuBtn = document.querySelector('.menu-btn');
  var sidebar = document.querySelector('.sidebar');
  var overlay = document.querySelector('.sidebar-overlay');

  function openSidebar() {

    if (sidebar) {
        sidebar.classList.add('open');
    }

    if (overlay) {
        overlay.classList.add('open');
    }

    document.body.classList.add('sidebar-open');

    document.documentElement.classList.add('sidebar-is-open');

    document.body.style.overflow = 'hidden';
}


function closeSidebar() {

    if (sidebar) {
        sidebar.classList.remove('open');
    }

    if (overlay) {
        overlay.classList.remove('open');
    }

    document.body.classList.remove('sidebar-open');

    document.documentElement.classList.remove('sidebar-is-open');

    document.body.style.overflow = '';
}

  if (menuBtn && sidebar) {
    menuBtn.addEventListener('click', function () {
      sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
  }
  if (overlay) overlay.addEventListener('click', closeSidebar);
  document.querySelectorAll('.sidebar nav a').forEach(function (a) {
    a.addEventListener('click', closeSidebar);
  });

  /* ---------------------------------------------------------------------
   * Toast notifications
   * ------------------------------------------------------------------- */
  var stack = document.getElementById('toastStack');

  function iconFor(type) {
    if (type === 'success') return '✓';
    if (type === 'error') return '⚠';
    if (type === 'warning') return '!';
    return 'ℹ';
  }

  window.showToast = function (message, type) {
    if (!stack || !message) return;
    type = type || 'info';
    var duration = 5000;
    var el = document.createElement('div');
    el.className = 'toast ' + type;
    el.innerHTML =
      '<span class="t-icon">' + iconFor(type) + '</span>' +
      '<span>' + message + '</span>' +
      '<button class="t-close" type="button" aria-label="Dismiss">✕</button>' +
      '<span class="t-progress"></span>';
    stack.appendChild(el);

    var removed = false;
    var remove = function () {
      if (removed) return;
      removed = true;
      el.style.transition = 'opacity .2s ease, transform .2s ease';
      el.style.opacity = '0';
      el.style.transform = 'translateX(12px)';
      setTimeout(function () { el.remove(); }, 200);
    };

    var remaining = duration;
    var timerStart = Date.now();
    var timeoutId = setTimeout(remove, duration);
    var progressEl = el.querySelector('.t-progress');

    el.addEventListener('mouseenter', function () {
      clearTimeout(timeoutId);
      remaining -= (Date.now() - timerStart);
      if (progressEl) progressEl.style.animationPlayState = 'paused';
    });
    el.addEventListener('mouseleave', function () {
      timerStart = Date.now();
      timeoutId = setTimeout(remove, Math.max(remaining, 400));
      if (progressEl) progressEl.style.animationPlayState = 'running';
    });

    el.querySelector('.t-close').addEventListener('click', function () {
      clearTimeout(timeoutId);
      remove();
    });
  };

  document.querySelectorAll('[data-flash]').forEach(function (node) {
    window.showToast(node.dataset.flash, node.dataset.flashType || 'info');
  });

  /* ---------------------------------------------------------------------
   * Scroll reveal
   * ------------------------------------------------------------------- */
  var revealEls = document.querySelectorAll('.reveal');

  /* Stagger reveal delay for elements that share a parent (e.g. grid of cards) */
  var revealParents = [];
  revealEls.forEach(function (el) {
    if (revealParents.indexOf(el.parentElement) === -1) revealParents.push(el.parentElement);
  });
  revealParents.forEach(function (parent) {
    if (!parent) return;
    var siblings = Array.prototype.filter.call(parent.children, function (c) {
      return c.classList && c.classList.contains('reveal');
    });
    siblings.forEach(function (el, i) {
      el.style.transitionDelay = Math.min(i * 55, 330) + 'ms';
    });
  });

  if ('IntersectionObserver' in window && revealEls.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    revealEls.forEach(function (el) { io.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('in-view'); });
  }

  /* ---------------------------------------------------------------------
   * Animated counters
   * ------------------------------------------------------------------- */
  document.querySelectorAll('[data-counter]').forEach(function (el) {
    var target = parseFloat(el.dataset.counter);
    if (isNaN(target)) return;
    var prefix = el.dataset.prefix || '';
    var decimals = el.dataset.decimals ? parseInt(el.dataset.decimals, 10) : 0;
    var duration = 900;
    var start = null;

    function step(ts) {
      if (start === null) start = ts;
      var progress = Math.min((ts - start) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3);
      var value = target * eased;
      el.textContent = prefix + value.toLocaleString('en-IN', {
        minimumFractionDigits: decimals, maximumFractionDigits: decimals
      });
      if (progress < 1) requestAnimationFrame(step);
    }
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      el.textContent = prefix + target.toLocaleString('en-IN', {
        minimumFractionDigits: decimals, maximumFractionDigits: decimals
      });
    } else {
      requestAnimationFrame(step);
    }
  });

  /* ---------------------------------------------------------------------
   * Button loading state on form submit (prevents double-submit)
   * ------------------------------------------------------------------- */
  document.querySelectorAll('form').forEach(function (form) {
    form.addEventListener('submit', function () {
      if (form.dataset.noLoading) return;
      var btn = form.querySelector('button[type="submit"], button.primary, button.danger, button.small-btn');
      if (btn && !btn.disabled) {
        btn.classList.add('btn-loading');
        btn.disabled = true;
      }
    });
  });

  /* ---------------------------------------------------------------------
   * Password show/hide toggle
   * ------------------------------------------------------------------- */
  document.querySelectorAll('.input-toggle[data-toggle-password]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = document.getElementById(btn.dataset.togglePassword);
      if (!input) return;
      var isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      btn.textContent = isHidden ? '🙈' : '👁';
    });
  });

  /* ---------------------------------------------------------------------
   * Payment form: card formatting + inline validation (no alert())
   * ------------------------------------------------------------------- */
  var paymentForm = document.getElementById('paymentForm');
  if (paymentForm) {
    var cardFields = document.getElementById('cardFields');
    var cashNote = document.getElementById('cashNote');
    var cardNumber = document.getElementById('cardNumber');
    var expiry = document.getElementById('expiry');
    var cvv = document.getElementById('cvv');
    var payButton = document.getElementById('payButton');
    var totalLabel = payButton ? payButton.dataset.totalLabel : '';

    function selectedMethod() {
      var checked = document.querySelector('input[name="method"]:checked');
      return checked ? checked.value : null;
    }

    function showFieldError(input, message) {
      var err = input.parentElement.querySelector('.field-error') ||
        (input.closest('.payment-label') && input.closest('.payment-label').querySelector('.field-error'));
      if (err) {
        err.textContent = message;
        err.classList.toggle('show', !!message);
      }
    }

    function updateMethod() {
      var isCard = selectedMethod() === 'card';
      if (cardFields) cardFields.style.display = isCard ? 'block' : 'none';
      if (cashNote) cashNote.style.display = isCard ? 'none' : 'flex';

      document.querySelectorAll('.method-option').forEach(function (item) {
        item.classList.toggle('active', item.querySelector('input').checked);
      });

      if (payButton) {
        payButton.textContent = isCard ? totalLabel : 'Confirm Cash Payment';
      }
    }

    if (cardNumber) {
      cardNumber.addEventListener('input', function () {
        var value = this.value.replace(/\D/g, '').slice(0, 16);
        this.value = value.replace(/(.{4})/g, '$1 ').trim();
        showFieldError(this, '');
      });
    }
    if (expiry) {
      expiry.addEventListener('input', function () {
        var value = this.value.replace(/\D/g, '').slice(0, 4);
        if (value.length > 2) value = value.slice(0, 2) + '/' + value.slice(2);
        this.value = value;
        showFieldError(this, '');
      });
    }
    if (cvv) {
      cvv.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 3);
        showFieldError(this, '');
      });
    }

    document.querySelectorAll('input[name="method"]').forEach(function (radio) {
      radio.addEventListener('change', updateMethod);
    });

    paymentForm.addEventListener('submit', function (event) {
      if (selectedMethod() !== 'card') return;

      var valid = true;
      var number = cardNumber ? cardNumber.value.replace(/\s/g, '') : '';

      if (number.length !== 16) {
        showFieldError(cardNumber, 'Enter a valid 16-digit card number.');
        valid = false;
      }
      if (expiry && !/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry.value)) {
        showFieldError(expiry, 'Use MM/YY format.');
        valid = false;
      }
      if (cvv && !/^\d{3}$/.test(cvv.value)) {
        showFieldError(cvv, 'Enter a 3-digit CVV.');
        valid = false;
      }

      if (!valid) {
        event.preventDefault();
        var btn = paymentForm.querySelector('button[type="submit"]');
        if (btn) { btn.classList.remove('btn-loading'); btn.disabled = false; }
        window.showToast('Please check your card details and try again.', 'error');
      }
    });

    updateMethod();
  }
})();

/* ========================================================================
 * GLOBAL PREMIUM RESPONSIVE SELECT
 * Robust version - menu is rendered directly on document.body
 * ======================================================================== */

(function initPremiumSelects() {

    function createPremiumSelect(select) {

        if (!select) {
            return;
        }

        if (select.dataset.premiumSelect === 'true') {
            return;
        }

        if (select.disabled) {
            return;
        }

        select.dataset.premiumSelect = 'true';
        select.classList.add('premium-select-native');

        var wrapper = document.createElement('div');
        wrapper.className = 'premium-select';

        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);

        /* -------------------------------------------------------------
         * Trigger
         * ------------------------------------------------------------- */

        var trigger = document.createElement('button');

        trigger.type = 'button';
        trigger.className = 'premium-select-trigger';

        trigger.setAttribute('aria-haspopup', 'listbox');
        trigger.setAttribute('aria-expanded', 'false');

        var value = document.createElement('span');
        value.className = 'premium-select-value';

        var arrow = document.createElement('span');
        arrow.className = 'premium-select-arrow';

        trigger.appendChild(value);
        trigger.appendChild(arrow);

        wrapper.appendChild(trigger);

        /* -------------------------------------------------------------
         * MENU
         *
         * IMPORTANT:
         * Menu is appended to BODY, not wrapper.
         * This prevents clipping by cards/containers.
         * ------------------------------------------------------------- */

        var menu = document.createElement('div');

        menu.className = 'premium-select-menu';
        menu.setAttribute('role', 'listbox');

        document.body.appendChild(menu);

        var searchInput = null;
        var options = [];

        var shouldSearch = select.options.length > 7;

        /* -------------------------------------------------------------
         * Selected value
         * ------------------------------------------------------------- */

        function getSelectedOption() {

            var selected =
                select.options[select.selectedIndex];

            if (!selected) {

                return {
                    text:
                        select.getAttribute(
                            'data-placeholder'
                        ) || 'Select an option',

                    value: ''
                };
            }

            return {
                text: selected.textContent.trim(),
                value: selected.value
            };
        }

        /* -------------------------------------------------------------
         * Update trigger
         * ------------------------------------------------------------- */

        function updateTrigger() {

            var selected = getSelectedOption();

            value.textContent =
                selected.text || 'Select an option';

            options.forEach(function (item) {

                var isSelected =
                    item.option.value === selected.value &&
                    !item.option.disabled;

                item.element.classList.toggle(
                    'is-selected',
                    isSelected
                );

                item.element.setAttribute(
                    'aria-selected',
                    isSelected ? 'true' : 'false'
                );
            });
        }

        /* -------------------------------------------------------------
         * Close other dropdowns
         * ------------------------------------------------------------- */

        function closeOtherDropdowns() {

            document
                .querySelectorAll(
                    '.premium-select.is-open'
                )
                .forEach(function (openWrapper) {

                    if (openWrapper === wrapper) {
                        return;
                    }

                    openWrapper.classList.remove(
                        'is-open'
                    );

                    var openTrigger =
                        openWrapper.querySelector(
                            '.premium-select-trigger'
                        );

                    if (openTrigger) {

                        openTrigger.setAttribute(
                            'aria-expanded',
                            'false'
                        );
                    }

                    var openMenu =
                        document.querySelector(
                            '.premium-select-menu[data-owner="' +
                            openWrapper.dataset.selectId +
                            '"]'
                        );

                    if (openMenu) {
                        openMenu.classList.remove(
                            'is-visible'
                        );
                    }
                });
        }

        /* -------------------------------------------------------------
         * Unique ID
         * ------------------------------------------------------------- */

        var selectId =
            'premium-select-' +
            Math.random()
                .toString(36)
                .substring(2, 10);

        wrapper.dataset.selectId = selectId;
        menu.dataset.owner = selectId;

        /* -------------------------------------------------------------
         * Position menu
         * ------------------------------------------------------------- */

        function positionMenu() {

            if (!wrapper.classList.contains('is-open')) {
                return;
            }

            var rect =
                trigger.getBoundingClientRect();

            var viewportWidth =
                window.innerWidth;

            var viewportHeight =
                window.innerHeight;

            var margin = 10;
            var gap = 7;

            /* ---------------------------------------------------------
             * Desktop
             * --------------------------------------------------------- */

            if (viewportWidth > 575) {

                menu.style.position = 'fixed';

                menu.style.left =
                    Math.round(rect.left) + 'px';

                menu.style.width =
                    Math.round(rect.width) + 'px';

                menu.style.right = 'auto';

                var menuHeight =
                    Math.min(
                        menu.scrollHeight || 300,
                        310
                    );

                var below =
                    viewportHeight -
                    rect.bottom -
                    margin;

                var above =
                    rect.top -
                    margin;

                if (
                    below < menuHeight &&
                    above > below
                ) {

                    menu.style.top = 'auto';

                    menu.style.bottom =
                        Math.max(
                            margin,
                            viewportHeight -
                            rect.top +
                            gap
                        ) + 'px';

                } else {

                    menu.style.bottom = 'auto';

                    menu.style.top =
                        Math.min(
                            viewportHeight -
                            menuHeight -
                            margin,

                            rect.bottom + gap
                        ) + 'px';
                }

                return;
            }

            /* ---------------------------------------------------------
             * MOBILE
             *
             * Full-width viewport dropdown.
             * --------------------------------------------------------- */

            var mobileLeft = 12;
            var mobileRight = 12;

            var mobileWidth =
                viewportWidth -
                mobileLeft -
                mobileRight;

            menu.style.position = 'fixed';

            menu.style.left =
                mobileLeft + 'px';

            menu.style.right =
                mobileRight + 'px';

            menu.style.width =
                mobileWidth + 'px';

            var mobileMenuHeight =
                Math.min(
                    menu.scrollHeight || 300,
                    Math.floor(
                        viewportHeight * 0.55
                    ),
                    360
                );

            var mobileSpaceBelow =
                viewportHeight -
                rect.bottom -
                margin;

            var mobileSpaceAbove =
                rect.top -
                margin;

            /* Open upward */
            if (
                mobileSpaceBelow <
                    mobileMenuHeight &&
                mobileSpaceAbove >
                    mobileSpaceBelow
            ) {

                menu.style.top = 'auto';

                menu.style.bottom =
                    Math.max(
                        margin,
                        viewportHeight -
                        rect.top +
                        gap
                    ) + 'px';

            } else {

                menu.style.bottom = 'auto';

                menu.style.top =
                    Math.min(
                        viewportHeight -
                        mobileMenuHeight -
                        margin,

                        rect.bottom + gap
                    ) + 'px';
            }
        }

        /* -------------------------------------------------------------
         * Open
         * ------------------------------------------------------------- */

        function openDropdown() {

            closeOtherDropdowns();

            wrapper.classList.add('is-open');

            trigger.setAttribute(
                'aria-expanded',
                'true'
            );

            menu.classList.add('is-visible');

            /*
             * Make sure menu is above everything.
             */
            menu.style.zIndex = '2147483647';

            requestAnimationFrame(function () {

                positionMenu();

                requestAnimationFrame(function () {

                    positionMenu();
                });

                if (searchInput) {

                    setTimeout(function () {

                        searchInput.focus({
                            preventScroll: true
                        });

                    }, 50);
                }
            });
        }

        /* -------------------------------------------------------------
         * Close
         * ------------------------------------------------------------- */

        function closeDropdown() {

            wrapper.classList.remove(
                'is-open'
            );

            trigger.setAttribute(
                'aria-expanded',
                'false'
            );

            menu.classList.remove(
                'is-visible'
            );

            menu.style.top = '';
            menu.style.bottom = '';
            menu.style.left = '';
            menu.style.right = '';
            menu.style.width = '';
        }

        /* -------------------------------------------------------------
         * Select option
         * ------------------------------------------------------------- */

        function selectOption(option) {

            if (!option || option.disabled) {
                return;
            }

            var oldValue =
                select.value;

            select.value =
                option.value;

            updateTrigger();

            if (
                oldValue !== option.value
            ) {

                var event =
                    new Event(
                        'change',
                        {
                            bubbles: true
                        }
                    );

                select.dispatchEvent(event);
            }

            closeDropdown();

            trigger.focus({
                preventScroll: true
            });
        }

        /* -------------------------------------------------------------
         * Render options
         * ------------------------------------------------------------- */

        function renderOptions(
            filterText
        ) {

            options = [];

            /*
             * Remove old options.
             */
            Array.from(
                menu.children
            ).forEach(function (child) {

                if (
                    !child.classList.contains(
                        'premium-select-search'
                    )
                ) {

                    child.remove();
                }
            });

            var query =
                (filterText || '')
                    .toLowerCase()
                    .trim();

            var visibleCount = 0;

            Array.from(
                select.options
            ).forEach(function (
                option,
                index
            ) {

                if (option.disabled) {
                    return;
                }

                var text =
                    option.textContent.trim();

                if (
                    query &&
                    text
                        .toLowerCase()
                        .indexOf(query) === -1
                ) {
                    return;
                }

                var optionElement =
                    document.createElement(
                        'button'
                    );

                optionElement.type = 'button';

                optionElement.className =
                    'premium-select-option';

                optionElement.setAttribute(
                    'role',
                    'option'
                );

                optionElement.dataset.index =
                    index;

                var label =
                    document.createElement(
                        'span'
                    );

                label.textContent = text;

                var check =
                    document.createElement(
                        'span'
                    );

                check.className =
                    'premium-select-check';

                check.textContent = '✓';

                optionElement.appendChild(
                    label
                );

                optionElement.appendChild(
                    check
                );

                optionElement.addEventListener(
                    'click',
                    function (event) {

                        event.preventDefault();
                        event.stopPropagation();

                        selectOption(option);
                    }
                );

                menu.appendChild(
                    optionElement
                );

                options.push({
                    option: option,
                    element: optionElement
                });

                visibleCount++;
            });

            if (visibleCount === 0) {

                var empty =
                    document.createElement(
                        'div'
                    );

                empty.className =
                    'premium-select-empty';

                empty.textContent =
                    'No options found';

                menu.appendChild(
                    empty
                );
            }

            updateTrigger();
        }

        /* -------------------------------------------------------------
         * Search
         * ------------------------------------------------------------- */

        if (shouldSearch) {

            var searchWrapper =
                document.createElement(
                    'div'
                );

            searchWrapper.className =
                'premium-select-search';

            searchInput =
                document.createElement(
                    'input'
                );

            searchInput.type =
                'search';

            searchInput.placeholder =
                'Search...';

            searchInput.autocomplete =
                'off';

            searchWrapper.appendChild(
                searchInput
            );

            menu.appendChild(
                searchWrapper
            );

            searchInput.addEventListener(
                'input',
                function () {

                    renderOptions(
                        searchInput.value
                    );

                    positionMenu();
                }
            );

            searchInput.addEventListener(
                'click',
                function (event) {

                    event.stopPropagation();
                }
            );
        }

        renderOptions('');

        /* -------------------------------------------------------------
         * Trigger click
         * ------------------------------------------------------------- */

        trigger.addEventListener(
            'click',
            function (event) {

                event.preventDefault();
                event.stopPropagation();

                if (
                    wrapper.classList.contains(
                        'is-open'
                    )
                ) {

                    closeDropdown();

                } else {

                    openDropdown();
                }
            }
        );

        /* -------------------------------------------------------------
         * Keyboard
         * ------------------------------------------------------------- */

        trigger.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Enter' ||
                    event.key === ' '
                ) {

                    event.preventDefault();

                    if (
                        wrapper.classList.contains(
                            'is-open'
                        )
                    ) {

                        closeDropdown();

                    } else {

                        openDropdown();
                    }

                    return;
                }

                if (
                    event.key === 'Escape'
                ) {

                    closeDropdown();
                    return;
                }
            }
        );

        /* -------------------------------------------------------------
         * Existing select change
         * ------------------------------------------------------------- */

        select.addEventListener(
            'change',
            function () {

                updateTrigger();
            }
        );

        /* -------------------------------------------------------------
         * Outside click
         * ------------------------------------------------------------- */

        document.addEventListener(
            'click',
            function (event) {

                if (
                    wrapper.contains(
                        event.target
                    )
                ) {
                    return;
                }

                if (
                    menu.contains(
                        event.target
                    )
                ) {
                    return;
                }

                closeDropdown();
            }
        );

        /* -------------------------------------------------------------
         * ESC
         * ------------------------------------------------------------- */

        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape'
                ) {

                    closeDropdown();
                }
            }
        );

        /* -------------------------------------------------------------
         * Resize
         * ------------------------------------------------------------- */

        window.addEventListener(
            'resize',
            function () {

                if (
                    wrapper.classList.contains(
                        'is-open'
                    )
                ) {

                    positionMenu();
                }
            }
        );

        /* -------------------------------------------------------------
         * Scroll
         * ------------------------------------------------------------- */

        window.addEventListener(
            'scroll',
            function () {

                if (
                    wrapper.classList.contains(
                        'is-open'
                    )
                ) {

                    positionMenu();
                }
            },
            {
                passive: true
            }
        );

        updateTrigger();
    }


    /* ====================================================================
     * INITIALIZE ALL SELECTS
     * ==================================================================== */

    function initPremiumSelects() {

        document
            .querySelectorAll(
                'select:not([data-no-premium-select])'
            )
            .forEach(function (select) {

                createPremiumSelect(
                    select
                );
            });
    }


    /*
     * Run immediately.
     */
    initPremiumSelects();


    /*
     * Also run after dynamically loaded content.
     */
    var premiumSelectObserver =
        new MutationObserver(
            function () {

                initPremiumSelects();
            }
        );

    premiumSelectObserver.observe(
        document.body,
        {
            childList: true,
            subtree: true
        }
    );

})();

import 'bootstrap';
import $ from 'jquery';

window.$ = window.jQuery = $;

function setAvailabilityMessage(htmlMessage, type = 'secondary', isUpdating = false) {
    const box = $('#availability');
    if (!box.length) return;

    if (isUpdating) {
        box.addClass('is-updating');
    } else {
        box.removeClass('is-updating');
    }

    box.fadeOut(120, function () {
        box.removeClass('alert-secondary alert-success alert-danger alert-info')
            .addClass(`alert-${type}`)
            .html(htmlMessage)
            .fadeIn(180);
    });
}

$(document).ready(function () {
    // Ajax availability check
    $(document).on('change', '#course_id', function () {
        const id = this.value;
        const template = $(this).data('availability-template');

        if (!id) {
            setAvailabilityMessage('Pilih kursus untuk melihat sisa kursi melalui Ajax.', 'secondary');
            return;
        }

        setAvailabilityMessage('<em>Memeriksa ketersediaan kursi...</em>', 'secondary', true);

        const url = template ? String(template).replace('__COURSE__', encodeURIComponent(id)) : `/courses/${id}/availability`;

        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success(data) {
                const courseName = data.course || 'Kursus';
                const remaining = data.remaining !== undefined ? data.remaining : 0;
                const statusText = data.status && data.status !== 'open' ? ` (Status: ${data.status})` : '';
                const msg = `<strong>${courseName}</strong> — Tersisa <strong>${remaining} kursi</strong>${statusText}.`;
                setAvailabilityMessage(msg, 'success');
            },
            error() {
                setAvailabilityMessage('<strong>Gagal:</strong> Tidak dapat memuat data ketersediaan kursi.', 'danger');
            }
        });
    });

    // Scroll reveal observer
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));
    } else {
        $('.reveal-on-scroll').addClass('revealed');
    }
});


// Auth & Dashboard micro-interactions
$(document).ready(function () {
    // Password show/hide toggle
    $(document).on('click', '.js-toggle-password', function () {
        const targetId = $(this).data('target');
        const input = document.getElementById(targetId);
        if (!input) return;

        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        $(this).find('.icon-eye').toggleClass('d-none', isPassword);
        $(this).find('.icon-eye-off').toggleClass('d-none', !isPassword);
    });

    // Loading state on auth form submission
    $(document).on('submit', '.js-loading-form', function () {
        const btn = $(this).find('.js-submit-btn');
        btn.prop('disabled', true);
        btn.find('.btn-label').addClass('d-none');
        btn.find('.btn-spinner').removeClass('d-none').addClass('d-inline-flex');
    });

    // Profile photo preview before upload
    $(document).on('change', '.js-avatar-input', function (e) {
        const file = e.target.files && e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (ev) {
            $('.js-avatar-preview').attr('src', ev.target.result).removeClass('d-none');
            $('.js-avatar-fallback').addClass('d-none');
        };
        reader.readAsDataURL(file);
    });

    // Admin mobile sidebar drawer
    $(document).on('click', '.js-admin-sidebar-open', function () {
        $('#adminSidebar, .admin-backdrop').addClass('is-open');
    });
    $(document).on('click', '.js-admin-sidebar-close', function () {
        $('#adminSidebar, .admin-backdrop').removeClass('is-open');
    });

    // Lightweight counter animation for Admin dashboard stats
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!prefersReducedMotion) {
        $('.js-counter').each(function () {
            const el = $(this);
            const target = parseInt(el.data('target'), 10);
            if (isNaN(target) || target <= 0) return;

            let current = 0;
            const step = Math.max(1, Math.ceil(target / 15));
            const timer = setInterval(function () {
                current += step;
                if (current >= target) {
                    el.text(target);
                    clearInterval(timer);
                } else {
                    el.text(current);
                }
            }, 25);
        });
    }
});

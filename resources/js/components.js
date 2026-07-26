/**
 * SkulBase Component Utilities — Phase 23.4
 * Modal, Toast, Dropdown, and Loading state management.
 */
(function() {
    'use strict';

    /* ── Modal System ────────────────────────────────── */
    window.SBModal = {
        show: function(id) {
            var el = document.getElementById(id);
            if (el) {
                el.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                var firstInput = el.querySelector('input, select, textarea');
                if (firstInput) setTimeout(function() { firstInput.focus(); }, 100);
            }
        },
        hide: function(id) {
            var el = document.getElementById(id);
            if (el) {
                el.style.display = 'none';
                document.body.style.overflow = '';
            }
        },
        confirm: function(options) {
            var id = 'sb-confirm-modal';
            var existing = document.getElementById(id);
            if (existing) existing.remove();

            var modal = document.createElement('div');
            modal.id = id;
            modal.className = 'sb-modal-backdrop';
            modal.style.cssText = 'display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);z-index:var(--z-modal-backdrop);align-items:center;justify-content:center;padding:var(--space-6);';

            var variantColors = {
                danger: { bg: '#FEF2F2', fg: '#EF4444', btn: 'sb-btn-danger' },
                warning: { bg: '#FFFBEB', fg: '#F59E0B', btn: 'sb-btn-warning' },
                info: { bg: '#EFF6FF', fg: '#3B82F6', btn: 'sb-btn-primary' },
            };
            var v = variantColors[options.variant || 'danger'];

            modal.innerHTML = '<div class="sb-modal-dialog" style="max-width:420px;width:100%;background:var(--card);border-radius:var(--radius-xl);box-shadow:var(--shadow-modal);overflow:hidden;">' +
                '<div class="sb-modal-body" style="padding:var(--space-6);text-align:center;">' +
                    '<div style="width:48px;height:48px;border-radius:50%;background:' + v.bg + ';color:' + v.fg + ';display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-4);">' +
                        (options.icon || '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>') +
                    '</div>' +
                    '<h3 style="margin:0 0 var(--space-2);font-size:var(--font-size-lg);font-weight:var(--font-weight-semibold);color:var(--text-heading);">' + (options.title || 'Are you sure?') + '</h3>' +
                    '<p style="margin:0;font-size:var(--font-size-sm);color:var(--text-muted);">' + (options.message || 'This action cannot be undone.') + '</p>' +
                '</div>' +
                '<div class="sb-modal-footer" style="display:flex;gap:var(--space-3);justify-content:center;padding:var(--space-4) var(--space-6);border-top:1px solid var(--border);background:var(--gray-50);">' +
                    '<button type="button" onclick="SBModal.hide(\'' + id + '\')" class="sb-btn sb-btn-ghost">' + (options.cancelLabel || 'Cancel') + '</button>' +
                    '<button type="button" onclick="' + (options.onConfirm || 'this.closest(\'.sb-modal-backdrop\').remove()') + ';SBModal.hide(\'' + id + '\')" class="sb-btn ' + v.btn + '">' + (options.confirmLabel || 'Confirm') + '</button>' +
                '</div>' +
            '</div>';

            document.body.appendChild(modal);
            SBModal.show(id);

            modal.addEventListener('click', function(e) {
                if (e.target === modal) SBModal.hide(id);
            });
            document.addEventListener('keydown', function handler(e) {
                if (e.key === 'Escape') {
                    SBModal.hide(id);
                    document.removeEventListener('keydown', handler);
                }
            });
        }
    };

    /* ── Toast Notification System ───────────────────── */
    window.SBToast = {
        container: null,
        init: function() {
            if (!this.container) {
                this.container = document.createElement('div');
                this.container.id = 'sb-toast-container';
                this.container.style.cssText = 'position:fixed;top:80px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;max-width:400px;';
                document.body.appendChild(this.container);
            }
        },
        show: function(options) {
            this.init();
            var variantStyles = {
                success: { bg: '#F0FDF4', border: '#10B981', color: '#065F46', icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>' },
                error: { bg: '#FEF2F2', border: '#EF4444', color: '#991B1B', icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>' },
                warning: { bg: '#FFFBEB', border: '#F59E0B', color: '#92400E', icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>' },
                info: { bg: '#EFF6FF', border: '#3B82F6', color: '#1E40AF', icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>' },
            };
            var v = variantStyles[options.variant || 'success'];
            var toast = document.createElement('div');
            toast.style.cssText = 'display:flex;align-items:flex-start;gap:10px;padding:12px 16px;border-radius:var(--radius-lg);background:' + v.bg + ';border:1px solid ' + v.border + ';box-shadow:0 4px 12px rgba(0,0,0,0.1);animation:sb-slide-in-right 0.2s ease;font-size:var(--font-size-sm);color:' + v.color + ';cursor:pointer;';
            toast.innerHTML = '<span style="flex-shrink:0;margin-top:1px;">' + v.icon + '</span>' +
                '<span style="flex:1;font-weight:var(--font-weight-medium);">' + (options.message || '') + '</span>' +
                '<button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;padding:0;color:inherit;opacity:0.6;flex-shrink:0;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>';
            toast.addEventListener('click', function() { toast.remove(); });
            this.container.appendChild(toast);

            var duration = options.duration || 5000;
            if (duration > 0) {
                setTimeout(function() {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    toast.style.transition = 'all 0.2s ease';
                    setTimeout(function() { toast.remove(); }, 200);
                }, duration);
            }
        },
        success: function(message, opts) { this.show(Object.assign({ message: message, variant: 'success' }, opts || {})); },
        error: function(message, opts) { this.show(Object.assign({ message: message, variant: 'error' }, opts || {})); },
        warning: function(message, opts) { this.show(Object.assign({ message: message, variant: 'warning' }, opts || {})); },
        info: function(message, opts) { this.show(Object.assign({ message: message, variant: 'info' }, opts || {})); },
    };

    /* ── Global Dropdown Close ───────────────────────── */
    document.addEventListener('click', function(e) {
        document.querySelectorAll('.sb-dropdown-menu').forEach(function(menu) {
            if (!menu.parentElement.contains(e.target)) {
                menu.style.display = 'none';
            }
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.sb-dropdown-menu').forEach(function(menu) {
                menu.style.display = 'none';
            });
        }
    });

    /* ── Auto-dismiss Flash Messages ─────────────────── */
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.sb-flash').forEach(function(flash) {
            var dismissBtn = flash.querySelector('[data-dismiss]');
            if (dismissBtn) {
                dismissBtn.addEventListener('click', function() {
                    flash.style.opacity = '0';
                    flash.style.transform = 'translateY(-10px)';
                    flash.style.transition = 'all 0.2s ease';
                    setTimeout(function() { flash.remove(); }, 200);
                });
            }
        });

        document.addEventListener('click', function(e) {
            var dismissAlert = e.target.closest('[data-dismiss-alert]');
            if (dismissAlert) {
                var alert = dismissAlert.closest('.sb-alert');
                if (alert) {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.2s ease';
                    setTimeout(function() { alert.remove(); }, 200);
                }
            }

            var dismissWidget = e.target.closest('[data-dismiss-widget]');
            if (dismissWidget) {
                var card = dismissWidget.closest('.sb-card');
                if (card) {
                    card.style.opacity = '0';
                    card.style.transition = 'opacity 0.2s ease';
                    setTimeout(function() { card.remove(); }, 200);
                }
            }

            var dismissModal = e.target.closest('[data-dismiss-modal]');
            if (dismissModal) {
                var modalId = dismissModal.getAttribute('data-dismiss-modal');
                if (modalId) {
                    window.SBModal.hide(modalId);
                }
            }
        });
    });
})();

import { HASHNODE_HOST } from '../config/hashnode';

export function cleanHashnodeHtml(input, { hashnodeHost = HASHNODE_HOST, localPrefix = '/blogs/' } = {}) {
    if (!input) return '';
    if (typeof window === 'undefined' || typeof DOMParser === 'undefined') return input;

    const doc = new DOMParser().parseFromString(`<div id="__root">${input}</div>`, 'text/html');
    const root = doc.getElementById('__root');
    if (!root) return input;

    root.querySelectorAll('li > p:only-child').forEach((p) => {
        const li = p.parentElement;
        while (p.firstChild) li.insertBefore(p.firstChild, p);
        p.remove();
    });

    root.querySelectorAll('p').forEach((p) => {
        const onlyImg =
            p.children.length === 1 &&
            p.firstElementChild?.tagName === 'IMG' &&
            !(p.textContent || '').trim();
        if (onlyImg) {
            const img = p.firstElementChild;
            if (!img.hasAttribute('alt')) img.setAttribute('alt', '');
            p.replaceWith(img);
        }
    });

    root.querySelectorAll('a[target="_blank"]').forEach((a) => {
        const rel = (a.getAttribute('rel') || '').split(/\s+/).filter(Boolean);
        if (!rel.includes('noopener')) rel.push('noopener');
        if (!rel.includes('noreferrer')) rel.push('noreferrer');
        a.setAttribute('rel', rel.join(' '));
    });

    root.querySelectorAll('code[class]').forEach((code) => {
        const classes = code.className.split(/\s+/);
        const mapped = classes.map((c) => (c.startsWith('lang-') ? `language-${c.slice(5)}` : c));
        code.className = [...new Set(mapped)].join(' ');
    });

    root.querySelectorAll('pre code').forEach((code) => {
        if (code.querySelector('[class^="hljs"]')) {
            code.textContent = code.textContent || '';
        }
    });

    root.querySelectorAll('img').forEach((img) => {
        if (!img.hasAttribute('alt')) img.setAttribute('alt', '');
    });

    root.querySelectorAll('p').forEach((p) => {
        if (!(p.textContent || '').trim() && !p.querySelector('img, code, pre')) p.remove();
    });

    root.querySelectorAll('a[href]').forEach((a) => {
        const raw = a.getAttribute('href');
        if (!raw) return;

        let url;
        try {
            url = new URL(raw, window.location.origin);
        } catch {
            return;
        }

        if (url.hostname !== hashnodeHost) return;

        const parts = url.pathname.split('/').filter(Boolean);
        if (parts.length !== 1) return;

        const slug = parts[0].replace(/\/+$/, '');
        a.setAttribute('href', `${localPrefix}${slug}${url.hash || ''}`);
        if (a.getAttribute('target') === '_blank') a.removeAttribute('target');
    });

    return root.innerHTML.trim();
}

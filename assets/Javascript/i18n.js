const I18N = (() => {
    let messages = {};
    let locale = (window.APP_LOCALE || 'en'); // <- from PHP

    let _readyResolve;
    const ready = new Promise(res => {
        _readyResolve = res;
    });

    const load = async (loc) => {
        locale = loc;
        const [langRes, globalRes] = await Promise.all([
            fetch(`./../../assets/languages/${loc}.json`, {cache: 'no-store'}),
            fetch(`./../../assets/languages/common.json`, {cache: 'no-store'})
        ]);

        const [langMsgs, globalMsgs] = await Promise.all([
            langRes.json(),
            globalRes.json()
        ]);

        messages = {...globalMsgs, ...langMsgs};

        apply();
    };

    const PLACEHOLDER = /\{([a-zA-Z0-9_.-]+)\}/g;

    const t = (key, vars = {}) => {
        let str = messages[key] ?? key;

        // merge precedence: explicit vars > message keys
        const lookup = (name) =>
            (name in vars) ? vars[name] :
                (typeof messages[name] === 'string' ? messages[name] : undefined);

        // resolve nested placeholders up to 5 passes to avoid cycles
        let passes = 0;
        let changed = true;
        while (passes < 5 && changed && typeof str === 'string') {
            changed = false;
            str = str.replace(PLACEHOLDER, (m, name) => {
                const val = lookup(name);
                if (typeof val === 'string') {
                    changed = true;
                    return val;
                }
                return m; // leave as-is if unknown
            });
            passes++;
        }
        return str;
    };

    const apply = () => {
        document.querySelectorAll("[data-i18n]").forEach(el => {
            const key = el.dataset.i18n;
            const vars = {};
            for (const [k, v] of Object.entries(el.dataset)) if (k !== "i18n") vars[k] = v;
            el.textContent = t(key, vars);
        });
        document.querySelectorAll("[data-i18n-attr]").forEach(el => {
            el.dataset.i18nAttr.split(",").forEach(pair => {
                const [attr, key] = pair.split(":").map(s => s.trim());
                if (attr && key) el.setAttribute(attr, t(key));
            });
        });
        document.documentElement.lang = locale;
        document.documentElement.dir = ["ar", "he", "fa", "ur"].includes(locale) ? "rtl" : "ltr";

        _readyResolve?.();
        document.dispatchEvent(new CustomEvent('i18n:changed', {detail: {locale}}));
    };

    window.addEventListener("DOMContentLoaded", () => load(locale));
    return {
        load, t, ready, get locale() {
            return locale;
        }
    };
})();
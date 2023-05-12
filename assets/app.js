/* global Alpine, ClassicEditor */

function TriggerChatExpanded() {
    const sidebarExtendTrigger = document.querySelector('#sidebarExtendTrigger');
    const twitchChatContainer = document.querySelectorAll('.twitch-chat-container iframe');

    if (localStorage.getItem('sidebar-expanded') === 'true') {
        twitchChatContainer.forEach((elem) => {
            elem.setAttribute('src', elem.getAttribute('data-src'));
        });
    }
    sidebarExtendTrigger.addEventListener('click', () => {
        setTimeout(() => {
            if (localStorage.getItem('sidebar-expanded') === 'true') {
                twitchChatContainer.forEach((elem) => {
                    elem.setAttribute('src', elem.getAttribute('data-src'));
                });
            } else {
                twitchChatContainer.forEach((elem) => {
                    elem.setAttribute('src', '/blank.html');
                });
            }
        }, 500);
    });
}
window.TriggerChatExpanded = TriggerChatExpanded;

function fillInputsWithGivenData(data) {
    const streamer_select_slug1 = document.getElementById('streamer_select_slug1');
    const streamer_select_slug2 = document.getElementById('streamer_select_slug2');
    const streamer_select_slug3 = document.getElementById('streamer_select_slug3');
    const streamer_select_slug4 = document.getElementById('streamer_select_slug4');

    if (streamer_select_slug1 && data[0]) {
        streamer_select_slug1.setAttribute('value', data[0]);
    }
    if (streamer_select_slug2 && data[1]) {
        streamer_select_slug2.setAttribute('value', data[1]);
    }
    if (streamer_select_slug3 && data[2]) {
        streamer_select_slug3.setAttribute('value', data[2]);
    }
    if (streamer_select_slug4 && data[3]) {
        streamer_select_slug4.setAttribute('value', data[3]);
    }
}
window.fillInputsWithGivenData = fillInputsWithGivenData;

function MultiselectDropdown(options) {
    const config = {
        search: true,
        placeholder: '--',
        txtSelected: 'ausgewählt',
        txtAll: 'Alle',
        txtSearch: 'Suchen',
        ...options
    };

    function newEl(tag, attrs) {
        const e = document.createElement(tag);
        if (attrs !== undefined) {
            Object.keys(attrs).forEach(key => {
                if (key === 'class') {
                    Array.isArray(attrs[key]) ? attrs[key].forEach(o => o !== '' ? e.classList.add(o) : 0) : (attrs[key] !== '' ? e.classList.add(attrs[key]) : 0)
                } else if (key === 'style') {
                    Object.keys(attrs[key]).forEach(ks => {
                        e.style[ks] = attrs[key][ks];
                    });
                } else if (key === 'text') {
                    attrs[key] === '' ? e.innerHTML = '&nbsp;' : e.innerText = attrs[key];
                } else {
                    e[key] = attrs[key];
                }
            });
        }
        return e;
    }

    document.querySelectorAll("select[multiple]").forEach((el) => {
        const div = newEl('div', {
            class: 'multiselect-dropdown',
        });
        el.style.display = 'none';
        el.parentNode.insertBefore(div, el.nextSibling);

        const listWrap = newEl('div', { class: 'multiselect-dropdown-list-wrapper' });
        const list = newEl('div', { class: 'multiselect-dropdown-list' });
        const search = newEl('input', {
            class: ['multiselect-dropdown-search'],
            placeholder: config.txtSearch
        });

        listWrap.appendChild(search);
        div.appendChild(listWrap);
        listWrap.appendChild(list);
        el.loadOptions = () => {
            list.innerHTML = '';

            const op = newEl('div', { class: 'multiselect-dropdown-all-selector' });
            const ic = newEl('input', { class: 'checked:bg-slate-500', type: 'checkbox' });
            op.appendChild(ic);
            op.appendChild(newEl('label', { text: config.txtAll }));

            op.addEventListener('click', () => {
                op.classList.toggle('checked');
                op.querySelector("input").checked = !op.querySelector("input").checked;

                const ch = op.querySelector("input").checked;
                list.querySelectorAll(":scope > div:not(.multiselect-dropdown-all-selector)")
                    .forEach(i => {
                        if (i.style.display !== 'none') {
                            i.querySelector("input").checked = ch;
                            i.optEl.selected = ch;
                        }
                    });
                el.dispatchEvent(new Event('change'));
            });

            ic.addEventListener('click', () => {
                ic.checked = !ic.checked;
            });

            el.addEventListener('change', () => {
                let items = Array.from(list.querySelectorAll(":scope > div:not(.multiselect-dropdown-all-selector)")).filter(e => e.style.display !== 'none');
                let existsNotSelected = items.find(i => !i.querySelector("input").checked);

                if (ic.checked && existsNotSelected) {
                    ic.checked = false;
                } else if (ic.checked === false && existsNotSelected === undefined) {
                    ic.checked = true;
                }
            });

            list.appendChild(op);

            Array.from(el.options).map(o => {
                const op = newEl('div', { class: o.selected ? 'checked' : '', optEl: o });
                const ic = newEl('input', { class: 'checked:bg-slate-500', type: 'checkbox', checked: o.selected });
                op.appendChild(ic);
                op.appendChild(newEl('label', { text: o.text }));

                op.addEventListener('click', () => {
                    op.classList.toggle('checked');
                    op.querySelector("input").checked = !op.querySelector("input").checked;
                    op.optEl.selected = !!!op.optEl.selected;
                    el.dispatchEvent(new Event('change'));
                });

                ic.addEventListener('click', (ev) => {
                    ic.checked = !ic.checked;
                });

                o.listitemEl = op;
                list.appendChild(op);
            });

            div.listEl = listWrap;

            div.refresh = () => {
                div.querySelectorAll('span.option-text, span.placeholder').forEach(t => div.removeChild(t));
                let sels = Array.from(el.selectedOptions);

                sels.map(x => {
                    let c = newEl('span', {
                        class: 'option-text',
                        text: x.text,
                        srcOption: x
                    });
                    c.appendChild(newEl('span', {
                        class: ['option-del'].concat(['fa-solid']).concat(['fa-delete-left']),
                        onclick: (ev) => {
                            c.srcOption.listitemEl.dispatchEvent(new Event('click'));
                            div.refresh();
                            ev.stopPropagation();
                        }
                    }));

                    div.appendChild(c);
                });

                if (el.selectedOptions.length === 0) {
                    div.appendChild(newEl('span', {
                        class: 'placeholder',
                        text: el.attributes['placeholder']?.value ?? config.placeholder
                    }));
                }
            };
            div.refresh();
        }
        el.loadOptions();

        search.addEventListener('input', () => {
            list.querySelectorAll(":scope div:not(.multiselect-dropdown-all-selector)").forEach(d => {
                var txt = d.querySelector("label").innerText.toUpperCase();
                d.style.display = txt.includes(search.value.toUpperCase()) ? 'block' : 'none';
            });
        });

        div.addEventListener('click', () => {
            div.listEl.style.display = 'block';
            search.focus();
            search.select();
        });

        document.addEventListener('click', function(event) {
            if (!div.contains(event.target)) {
                listWrap.style.display = 'none';
                div.refresh();
            }
        });
    });
}
window.MultiselectDropdown = MultiselectDropdown;


if (localStorage.getItem('sidebar-expanded') == 'true') {
    document.querySelector('body').classList.add('sidebar-expanded');
} else {
    document.querySelector('body').classList.remove('sidebar-expanded');
}
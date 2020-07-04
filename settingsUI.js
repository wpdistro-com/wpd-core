let entries = {};
const setterFuncs = {
    "number": getIntegerValue,
    "string": getStringValue,
    "boolean": getBooleanValue
}
function getBooleanValue(element){
    return element.checked;
}

function getStringValue(element){
    return element.value;
}

function getIntegerValue(element){
    return parseInt(element.value);
}

function generateStringOption(id, value, option){
    let element = htmlToElement(`
    </br>
    <tr valign="top">
            <th scope="row">${option.title}</th>
            <td>
               <input type="text" style="width:50%" value="${value}" />
               <br/>
            </td>
          </tr>
    `);
    document.querySelector("#actualOptions").insertAdjacentElement('beforeend', element);
    return element.getElementsByTagName("input")[0];
}

function makeid(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
       result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
 }

 function htmlToElement(html) {
    var template = document.createElement('template');
    html = html.trim(); // Never return a text node of whitespace as the result
    template.innerHTML = html;
    return template.content.lastChild;
}

function generateNumberOption(id, value, option){
    let element = htmlToElement(`</br>
    <tr valign="top">
            <th scope="row">${option.title}</th>
            <td>
               <input type="text"style="width:50%" value="${value}" />
               <br/>
            </td>
          </tr>
    `);
    document.querySelector("#actualOptions").insertAdjacentElement('beforeend', element);
    return element.getElementsByTagName("input")[0];
}

function generateBooleanOption(id, value, option){
    let element = htmlToElement(`</br>
    <tr>
    <th scope="row">${option.title}</th>
    <td><input type="checkbox" ` + (value? `checked="checked"` : "") + `></td>
</tr>
    `);
    document.querySelector("#actualOptions").insertAdjacentElement('beforeend', element);
    return element.getElementsByTagName("input")[0];
}

function onButtonClick(element, form){
    for (let [key, value] of Object.entries(entries)) {
        value.element.disabled = true;
        let optionJson = jsonQ(WPDistroConfInit[value.plugin].settings[value.setting].value);
        if(value.path !== undefined){
            let optionPath = value["path"].split("/");
            WPDistroConfInit[value.plugin].settings[value.setting].value = optionJson.setPathValue(optionPath, setterFuncs[typeof value.value](value.element)).jsonQ_root;
        } else {
            WPDistroConfInit[value.plugin].settings[value.setting].value = setterFuncs[typeof value.value](value.element);
        }
    }
    for(let plugin of WPDistroConfInit) {
        if(plugin.settings === undefined) continue;
        for (let setting of plugin.settings) {
            if (setting.value instanceof Object) {
                form[setting.name].value = JSON.stringify(setting.value);
            } else {
                form[setting.name].value = setting.value;
            }
        }
    }
    form.submit();
    for (let [key, value] of Object.entries(entries)) {
        value.element.disabled = false;
    }
}



function generateOption(id, value, option){
    switch(typeof value){
        case "number":
            return generateNumberOption(id, value, option);
            break;
        case "string":
            return generateStringOption(id, value, option);
            break;
        case "boolean":
            return generateBooleanOption(id, value, option);
            break;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    for (var k = 0; k < WPDistroConfInit.length; k++) {
        let plugin = WPDistroConfInit[k];
        document.querySelector("#actualOptions").insertAdjacentHTML('beforeend', `</br>
        <h1>Settings for ${plugin.pluginName}</h1>`);
        if(plugin.error !== undefined) {
            document.querySelector("#actualOptions").insertAdjacentHTML('beforeend', `</br>
        Pro tento plugin nejsou nastavení.`);
            continue;
        }
        for (var j = 0; j < plugin.settings.length; j++) {
            var setting = plugin.settings[j];
            if (setting.value instanceof Object) {
                for (var option of setting.options) {
                    var pathArray = option.path.split("/");
                    var pathValue = setting.value;
                    for (var i = 0; i < pathArray.length; i++) {
                        pathValue = pathValue[pathArray[i]];
                    }
                    let id = makeid(7);
                    entries[id] = {}
                    entries[id].path = option.path;
                    entries[id].value = pathValue;
                    entries[id].setting = j;
                    entries[id].plugin = k;
                    entries[id].element = generateOption(id, pathValue, option);
                }
            } else {
                let id = makeid(7);
                entries[id] = {};
                entries[id].setting = j;
                entries[id].plugin = k;
                entries[id].value = setting.value;
                entries[id].element = generateOption(id, setting.value, setting);
            }
        }
    }
}, false);
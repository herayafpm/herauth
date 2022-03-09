
// First, checks if it isn't implemented yet.
if (!String.prototype.format) {
    String.prototype.format = function () {
        var args = arguments;
        return this.replace(/{(\d+)}/g, function (match, number) {
            return typeof args[number] != 'undefined'
                ? args[number]
                : match
                ;
        });
    };
}

var locale = 'id';
var localePath = '';
var localeSupport = []
var herlangList = []
var herlangLoadedFiles = []

function herlangjsSetLocaleSupport(list) {
    localeSupport = list
}
function herlangjsSetPathLocale(path) {
    localePath = path
}

function herlangjsSetLocale(localeSet) {
    locale = localeSet;
}

function herlangjs(line, ...args) {
    var line = herlangjsGetLine(line, args);
    return line;
}

function herlangjsGetLine(line, args = []) {
    if (line.indexOf('.') < 0) {
        return herlangjsFormatMessage(line, args);
    }

    [file, parsedLine] = herlangjsParseLine(line);
    var output = herlangjsTranslationOutput(file, parsedLine);
    if (output === null && locale.indexOf('-') > 0) {
        [locale] = locale.split('-', 2);

        [file, parsedLine] = herlangjsParseLine(line);

        output = herlangjsTranslationOutput(file, parsedLine);
    }

    // if still not found, try English
    if (output === null) {
        herlangjsSetLocale('en')
        [file, parsedLine] = herlangjsParseLine(line);
        output = herlangjsTranslationOutput(file, parsedLine);
    }

    output = output ?? line;

    return herlangjsFormatMessage(output, ...args);
}


function herlangjsTranslationOutput(file, parsedLine) {
    try {
        var output = herlangList[locale][file][parsedLine] ?? null;
        if (output !== null) {
            return output;
        }
        var current = null;
        parsedLine.split(".").map((el) => {
            if (current === null) {
                current = herlangList[locale][file] ?? null
            }
            output = current[el] ?? null;
            if (typeof output === 'object') {
                current = output;
            }

        });

        if (output !== null) {
            return output;
        }
        var row = parsedLine.substr(0, parsedLine.indexOf('.'))
        var key = parsedLine.substr(parsedLine.length + 1)

        return herlangList[locale][file][row][key] ?? null;
    } catch (error) {
        return parsedLine;
    }
}

function herlangjsFormatMessage(message, args) {
    if (args === []) {
        return message;
    }
    if (typeof message === 'array') {
        message = message.map((value, index) => {
            message[index] = herlangjsFormatMessage(value, index)
        })
        return message;
    }
    return message.format(args)
}

function herlangjsParseLine(line) {

    var file = line.substr(0, line.indexOf('.'))
    var line = line.substr(file.length + 1)

    try {
        if (herlangList[locale][file] === undefined || !(line in herlangList[locale][file])) {
            herlangjsLoad(file)
        }
    }
    catch (err) {
        herlangjsLoad(file)
    }

    return [file, line];
}


function herlangjsLoad(file, returned = false) {
    if (!(locale in herlangLoadedFiles)) {
        herlangLoadedFiles[locale] = []
    }

    if (herlangLoadedFiles[locale].includes(file)) {
        return []
    }

    if (!(locale in herlangList)) {
        herlangList[locale] = []
    }

    if (!(file in herlangList[locale])) {
        herlangList[locale][file] = []
    }
    var path = file;

    var lang = herLangRequireFile(path);

    if (returned) {
        return lang;
    }
    herlangLoadedFiles[locale].push(file)

    // Merge our string
    herlangList[locale][file] = lang;
}

function herLangRequireFile(path) {
    var strings = {}
    var request = new XMLHttpRequest();
    request.open('GET', localePath+'?file='+path, false);  // `false` makes the request synchronous
    request.onload = function (e) {
        if (request.readyState === 4) {
            if (request.status === 200) {
                strings = JSON.parse(request.responseText)
            }
        }
    }
    request.send(null);
    return strings;
}
'use strict';

function disabled(parts, boolean) {
    parts.disabled = boolean;
    if (parts.disabled === true) {
        parts.style.backgroundColor = "gray";
        parts.style.cursor = "default";
    } else {
        parts.style.backgroundColor = "#00A5BB";
        parts.style.cursor = "pointer";
    }
}

export { disabled };

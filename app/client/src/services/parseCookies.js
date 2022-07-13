const parseCookies = () => {
    const cookieArr = document.cookie.split(';');
    const cookieObject = {};
    cookieArr.forEach(cookie => {
        let keyValue = cookie.split('=');
        if (keyValue[0][0] === ' ') keyValue[0] = keyValue[0].substring(1);
        cookieObject[keyValue[0]] = keyValue[1];
    });
    return cookieObject;
}

export default parseCookies;
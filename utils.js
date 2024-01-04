export const sendRequest = async (url, formData) => {
    return await fetch(url, {
        method: 'POST',
        body: formData
    });
};

export const getSessionStorage = (key) => {
    return sessionStorage.getItem(key);
}

export const setSessionStorage = (key, value) => {
    sessionStorage.setItem(key, value);
}

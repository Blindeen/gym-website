export const sendRequest = async (url, formData) => {
    return await fetch(url, {
        method: 'POST',
        body: formData
    });
};

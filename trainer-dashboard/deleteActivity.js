import {sendRequest} from "../utils.js";

const onSubmit = (removeButton) => removeButton.addEventListener('click', async () => {
    const activityID = removeButton.getAttribute('data-id');
    const url = 'delete-activity.php?id=' + activityID;
    const response = await sendRequest(url);

    if (response.ok) {
        location.reload();
    }
});

export default onSubmit;

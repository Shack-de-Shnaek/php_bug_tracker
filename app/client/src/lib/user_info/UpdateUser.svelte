<script>
    import { currentUser, viewedUser, modalControl } from "../../store";
    import EmailField from "../misc/EmailField.svelte";
    import PasswordField from "../misc/PasswordField.svelte";
    import TextField from "../misc/TextField.svelte";

    let userEditOpen = false;    

    let updateName;
    let updateSurname;
    let updateEmail;
    let updatePassword;

    viewedUser.subscribe(value => {
        updateName = value.name;
        updateSurname = value.surname;
        updateEmail = value.email;
        updatePassword = value.password;
    });

    const handleUpdate = async(type) => {
        let data = `${encodeURIComponent('id')}=${encodeURIComponent($currentUser.idUser)}&${encodeURIComponent('column')}=${encodeURIComponent(type)}`;
        let value;
        switch (type) {
            case 'name':
                value = updateName;
                break;
            case 'surname':
                value = updateSurname;
                break;
            case 'email':
                value = updateEmail;
                break;
            case 'password':
                value = updatePassword;
                break;
        }
        data += `&${encodeURIComponent('value')}=${encodeURIComponent(value)}`;
        try {
            const res = await fetch('/api/users', {
                method: 'PUT',
                body: data
            });
            if(res.ok) {
                modalControl.show('success', 'User info has been updated');
                viewedUser.update(user => {
                    user[type] = value;
                    return user;
                });
                currentUser.update(user => {
                    user[type] = value;
                    return user;
                });
            }
        } catch(e) {
            modalControl.show('error', 'Could not update user info');
            console.log(e);
        }
    }
</script>

<div class="user-edit-container">
    <button class="user-edit-button" on:click={() => userEditOpen = !userEditOpen}>Edit</button>
    <div class="edit-forms-container" class:open={userEditOpen}>
        <form class="name-container field-container" on:submit|preventDefault={() => handleUpdate('name')}>
            <TextField label='Name' bind:value={updateName} minLength={2} />
            <button type="submit" class="save-button">Save</button>
        </form>
        <form class="surname-container field-container" on:submit|preventDefault={() => handleUpdate('surname')}>
            <TextField label='Surname' bind:value={updateSurname} minLength={2} />
            <button type="submit" class="save-button">Save</button>
        </form>
        <form class="email-container field-container" on:submit|preventDefault={() => handleUpdate('email')}>
            <EmailField bind:value={updateEmail} />
            <button type="submit" class="save-button">Save</button>
        </form>
        <form class="password-container field-container" on:submit|preventDefault={() => handleUpdate('password')}>
            <PasswordField bind:value={updatePassword} />
            <button type="submit" class="save-button">Save</button>
        </form>
    </div>
</div>

<style>
    .edit-forms-container {
        width: 100%;
        max-height: 0;
        margin: 0;
        padding: 0;
        overflow: hidden;
        transition: max-height 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .edit-forms-container.open {
        max-height: 18rem;
    }

    .user-edit-container {
        padding: 0.5rem;
    }

    .field-container {
        max-width: 100%;
        margin: -0.5rem 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }
</style>
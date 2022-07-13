<script>
	import { currentUserId, viewedProject, userIsProjectLeader, modalControl } from '../../../store';
	import capitalizeFirst from "../../../services/capitalizeFirst";
	
	export let user;

	const handleRemoveMember = async() => {
		try {
			const data = `${encodeURIComponent('id')}=${encodeURIComponent($viewedProject.idProject)}&${encodeURIComponent('memberid')}=${encodeURIComponent(user.idUser)}`;
			const res = await fetch(`/api/projects/members`, {
				method: 'DELETE',
				body: data
			});
			if(res.ok) {
				modalControl.show('success', 'Removed the user');
				viewedProject.update(value => {
					value.members = value.members.filter(filterMember => filterMember.idUser !== user.idUser);
					return value;
				});
			} else {
				modalControl.show('error', 'Could not remove the user');
			}
		} catch(e) {
			modalControl.show('error', 'Could not remove the user');
			console.log(e);
		}
	}
</script>

<div class="member-container">
	<span class="member-info">
		<a href={`/users/${user.idUser}`} class="name">{capitalizeFirst(user.name)} {capitalizeFirst(user.surname)}</a>
		<a href={`mailto:${user.email}`} class="email">{user.email}</a>
	</span>
	{#if $userIsProjectLeader == true && user.idUser !== $currentUserId}
	<button class="remove-button" on:click={handleRemoveMember}>Remove</button>
	{/if}
</div>

<style>
	.member-container {
		width: 100%;
		min-height: 3rem;
		padding: 0.5rem;
		border-radius: 5px;
		background-color: var(--primary-white);
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-wrap: wrap;
		gap: 0.5rem;
	}

	.member-container:nth-child(even) {
		background-color: #eee;
	}

	.member-info {
		width: 80%;
		height: 100%;
		display: flex;
		justify-content: space-between;
		gap: 0.5rem;
	}

	.remove-button {
        background-color: var(--secondary-red);
		margin: 0;
		padding: 5px;
    }

	.remove-button:active {
		background-color: var(--secondary-yellow);
	}
</style>

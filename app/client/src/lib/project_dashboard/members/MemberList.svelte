<script>
	import { viewedProject } from '../../../store';
	import Member from "./Member.svelte";
</script>

<div class="post-list">
	{#each $viewedProject.members as member}
	<Member user={member} />
	{/each}
</div>

<style>
	.post-list {
		width: var(--clamped-width);
		margin: 0 auto;
		margin-top: 3rem;
		padding: 0.25rem;
		border-radius: 10px;
		box-shadow: 2px 2px 7px 3px #777;
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 1rem;
	}
</style>

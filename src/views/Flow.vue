<template>
	<div>
		<NcMultiselect v-model="selectedScript"
			class="section-details"
			:options="scripts"
			:placeholder="t('files_scripts', 'Select an action to perform')"
			track-by="id"
			label="title" />
	</div>
</template>

<script>
import { translate as t } from '../l10n'
import { api } from '../api/script'
import { NcMultiselect } from '@nextcloud/vue'

export default {
	name: 'Flow',
	components: {
		NcMultiselect,
	},
	props: {
		value: String,
	},
	data() {
		return {
			scripts: [],
			selectedScript: null,
		}
	},
	watch: {
		selectedScript(newValue) {
			const id = newValue ? newValue.id : -1
			this.$emit('input', id.toString())
		},
	},

	async mounted() {
		this.scripts = await api.getScripts()
		const value = parseInt(this.value)
		this.selectedScript = this.scripts.find(script => {
			return script.id === value
		})
	},
	methods: {
		t,
	},
}
</script>

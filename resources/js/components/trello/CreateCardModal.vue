<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3'
import { Plus } from '@lucide/vue'
import { computed, ref } from 'vue'

import MemberAvatar from '@/components/trello/MemberAvatar.vue'
import { Button } from '@/components/ui/button'
import { Checkbox } from '@/components/ui/checkbox'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { store as cardStore } from '@/routes/boards/lists/cards'
import type { Team } from '@/types'

const props = defineProps<{
  list: { id: number; name: string }
}>()

const page = usePage()
const open = ref(false)
const form = useForm({ title: '', description: '', member_ids: [] as number[] })

const teamMembers = computed(
  () => (page.props.teamMembers as Array<{ id: number; name: string }> | undefined) ?? [],
)

function toggleMember(id: number, checked: boolean) {
  form.member_ids = checked
    ? [...form.member_ids, id]
    : form.member_ids.filter((memberId) => memberId !== id)
}

function submit() {
  const currentTeam = page.props.currentTeam as Team | null
  const board = page.props.board as { slug: string } | null

  if (!currentTeam || !board) {
    return
  }

  form.post(cardStore({
    current_team: currentTeam.slug,
    board: board.slug,
    list: props.list.id,
  }).url, {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      open.value = false
    },
  })
}
</script>

<template>
  <Dialog v-model:open="open">
    <DialogTrigger as-child>
      <slot name="trigger">
        <button
          type="button"
          class="flex w-full shrink-0 items-center gap-1.5 rounded-xl px-2 py-1.5 text-left text-xs font-medium text-muted-foreground transition-colors hover:bg-background hover:text-foreground"
        >
          <Plus class="h-3.5 w-3.5" />
          Add card
        </button>
      </slot>
    </DialogTrigger>

    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>Create card</DialogTitle>
        <DialogDescription>
          Add a new card to "{{ list.name }}".
        </DialogDescription>
      </DialogHeader>

      <form class="space-y-4" @submit.prevent="submit">
        <div class="space-y-2">
          <Label for="create-card-title">Title</Label>
          <Input id="create-card-title" v-model="form.title" placeholder="e.g. Design the landing page" autofocus />
          <p v-if="form.errors.title" class="text-sm text-destructive">
            {{ form.errors.title }}
          </p>
        </div>

        <div class="space-y-2">
          <Label for="create-card-description">Description</Label>
          <textarea
            id="create-card-description"
            v-model="form.description"
            rows="3"
            placeholder="Add more detail (optional)"
            class="border-input placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 flex w-full rounded-md border bg-transparent px-3 py-2 text-base shadow-xs outline-none transition-[color,box-shadow] focus-visible:ring-[3px] md:text-sm"
          />
          <p v-if="form.errors.description" class="text-sm text-destructive">
            {{ form.errors.description }}
          </p>
        </div>

        <div v-if="teamMembers.length" class="space-y-2">
          <Label>Members</Label>
          <div class="max-h-40 space-y-1 overflow-y-auto rounded-md border border-border p-2">
            <Label
              v-for="member in teamMembers"
              :key="member.id"
              class="flex items-center gap-2 rounded px-1 py-1.5 text-sm font-normal hover:bg-muted/50"
            >
              <Checkbox
                :model-value="form.member_ids.includes(member.id)"
                @update:model-value="(checked) => toggleMember(member.id, checked === true)"
              />
              <MemberAvatar :member="member" size="xs" />
              {{ member.name }}
            </Label>
          </div>
        </div>

        <DialogFooter>
          <Button type="button" variant="outline" @click="open = false">
            Cancel
          </Button>
          <Button type="submit" :disabled="form.processing">
            Create
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>

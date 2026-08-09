<script setup lang="ts">
import { CalendarDays } from '@lucide/vue'
import { ref } from 'vue'
import CardDetailsModal from '@/components/trello/CardDetailsModal.vue'
import MemberAvatar from '@/components/trello/MemberAvatar.vue'

const VISIBLE_MEMBERS = 4

defineProps<{
  card: {
    id: number
    title: string
    description: string | null
    dueAt: string | null
    members: Array<{ id: number; name: string }>
  }
  list: { id: number; name: string }
}>()

const open = ref(false)
</script>

<template>
  <article
    class="cursor-pointer rounded-xl border border-border/60 bg-card p-3 shadow-sm transition-all hover:-translate-y-0.5 hover:border-border hover:shadow-md active:translate-y-0"
    tabindex="0"
    role="button"
    @click="open = true"
    @keydown.enter="open = true"
  >
    <p class="text-sm leading-snug font-medium text-foreground">{{ card.title }}</p>

    <p v-if="card.description" class="mt-1.5 line-clamp-2 text-xs text-muted-foreground">
      {{ card.description }}
    </p>

    <div v-if="card.dueAt || card.members.length" class="mt-3 flex items-center justify-between gap-2">
      <span
        v-if="card.dueAt"
        class="inline-flex shrink-0 items-center gap-1 rounded-full bg-muted px-2 py-0.5 text-[10px] font-medium text-muted-foreground"
      >
        <CalendarDays class="h-3 w-3" />
        {{ card.dueAt }}
      </span>
      <span v-else />

      <div v-if="card.members.length" class="flex -space-x-2">
        <MemberAvatar
          v-for="member in card.members.slice(0, VISIBLE_MEMBERS)"
          :key="member.id"
          :member="member"
          size="xs"
        />
        <div
          v-if="card.members.length > VISIBLE_MEMBERS"
          class="flex size-5 items-center justify-center rounded-full bg-muted text-[9px] font-medium text-muted-foreground ring-2 ring-background"
        >
          +{{ card.members.length - VISIBLE_MEMBERS }}
        </div>
      </div>
    </div>
  </article>

  <CardDetailsModal v-model:open="open" :card="card" :list="list" />
</template>

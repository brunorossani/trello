<script setup lang="ts">
import { Pencil } from '@lucide/vue'
import { ref } from 'vue'
import CardItem from '@/components/trello/CardItem.vue'
import CreateCardModal from '@/components/trello/CreateCardModal.vue'
import EditBoardListModal from '@/components/trello/EditBoardListModal.vue'
import { Button } from '@/components/ui/button'

defineProps<{
  list: {
    id: number
    name: string
    cards: Array<{
      id: number
      title: string
      description: string | null
      dueAt: string | null
      members: Array<{ id: number; name: string }>
    }>
  }
}>()

const editOpen = ref(false)
</script>

<template>
  <section class="flex w-[300px] shrink-0 flex-col gap-3 rounded-2xl border border-sidebar-border/70 bg-muted/40 p-3">
    <!-- Header -->
    <div class="flex shrink-0 items-center justify-between gap-2 px-1">
      <h2 class="truncate text-sm font-semibold text-foreground">{{ list.name }}</h2>
      <div class="flex shrink-0 items-center gap-1.5">
        <span class="rounded-full bg-background px-2 py-0.5 text-[11px] font-medium text-muted-foreground shadow-xs">
          {{ list.cards.length }}
        </span>
        <Button
          variant="ghost"
          size="icon"
          class="h-6 w-6 text-muted-foreground opacity-70 transition-opacity hover:text-foreground hover:opacity-100 focus-visible:opacity-100"
          @click="editOpen = true"
        >
          <Pencil class="h-3.5 w-3.5" />
        </Button>
      </div>
    </div>

    <!-- Cards — scroll interno -->
    <div class="flex flex-col gap-2 overflow-y-auto">
      <CardItem v-for="card in list.cards" :key="card.id" :card="card" :list="list" />

      <p
        v-if="!list.cards.length"
        class="rounded-xl border border-dashed border-sidebar-border/60 px-3 py-6 text-center text-xs text-muted-foreground"
      >
        No cards yet
      </p>
    </div>

    <!-- Add card — fijo abajo -->
    <CreateCardModal :list="list" />

    <EditBoardListModal v-model:open="editOpen" :list="list" />
  </section>
</template>

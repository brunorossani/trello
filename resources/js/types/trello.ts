export type TrelloCard = {
  id: number;
  title: string;
  description: string | null;
  position: number;
  dueAt: string | null;
  isArchived: boolean;
  members: Array<{
    id: number;
    name: string;
  }>;
};

export type TrelloList = {
  id: number;
  name: string;
  position: number;
  cards: TrelloCard[];
};

export type TrelloBoard = {
  id: number;
  name: string;
  slug: string | null;
  isPrivate: boolean;
  lists: TrelloList[];
};

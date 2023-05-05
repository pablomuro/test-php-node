import { Entity, BaseEntity, PrimaryGeneratedColumn, Column } from "typeorm";

@Entity()
export class Joke extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  jokeText: string;
}

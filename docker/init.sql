create table books (
    id serial primary key,
    name varchar(50) not null,
    author varchar(40) default '',
    publication date not null check(publication < now()),
    pages int not null check(pages > 0),
    descr text
);
comment on table books is 'Книги';

create index books_name_ind on books (name);
comment on column books.name is 'Наименование';

create index books_author_ind on books (author);
comment on column books.author is 'Автор';

create index books_publication_ind on books (publication);
comment on column books.publication is 'Дата публикации';

create index books_pages_ind on books (pages);
comment on column books.pages is 'Количество страниц';

comment on column books.descr is 'Описание книги';
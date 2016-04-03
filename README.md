Jackalope 2
===========

[![Build Status](https://travis-ci.org/jackalope2/jackalope2.svg?branch=master)](https://travis-ci.org/jackalope2/jackalope2)
[![StyleCI](https://styleci.io/repos/<repo-id>/shield)](https://styleci.io/repos/<repo-id>)

A POC/WIP for Jackalope 2

General concepts:

- Modular: Storage, Search, Versioning, etc all idependent and combinable.
- PHPCR layer built upon simpler base layer.
- Support for soft transactions.
- Lazy loading of properties (depending on storage).
- Mandatory UUIDs.
- PHP 7 only.
- Clean.

## Modular

The current Jackalope implies to the implemention (Doctrine DBAL, Jackrabbit)
that it must handle everything - not only storage, but also versioning and
search. This is mitigated to some extent by providing helper functions in the
main library, however it is ultimately the implementations responsibility.

It is also very hard work to create new implementations due to the organically
evolved API which grew out of a PHPCR Jackrabbit client.

This second iteration will fully decouple storage, versioning, search (and
whatever else). This will mean creating, for example, a file system backend
will be realtively simple, and it can then be combined with, for example, an
Zend Search module and a DBAL versioning module.

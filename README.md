# L'allée des fées

### Questions :

* Y a t-il des cours réservés aux hommes / femmes ? Oui
* L'idée des notifications est elle bonne ? Quelles notifications ?
* Notification interne + email ? Ou que l'un ou l'autre ?
  * Notification lors de la préinscription
  * Notification lors d'une annulation de présence au cours
  * Notification lors de demande de suppression de compte
  * Utilisateur : notification d'annulation de cours
  * Utilisateur : notification de déplacement de groupe
  * Utilisateur : inscription validée

##### TODO : 
 - Handle both subscription approval / denial
 - Handle Group CRUD: Thinking about automating group creation according to available schedules per lesson.
 - When the subscription is approved, query available groups for said lesson, return array that would look like so:
```
{
    "default": 7,
    "options": {
        "Lundi": {
            "1": "Groupe Lundi 08:00 - 09:30 (1/14)",
            "2": "Groupe Lundi 09:45 - 10:30 (8/14)",
        },
        "Mardi": {
            ...
        }
    }
}
```
- If no room is available for the 1st choice, set 2nd choice as default. If none, set default to null.
- Remove groups that are full?
- [Note for preselecting element](https://jsfiddle.net/L87xem1y/1/)

<?php

const ERROR_404 = "Erreur 404: Page introuvable, vous avez été redirigé sur la page d'accueil";

//used in userController:
const USER_CREATED = "Vous êtes désormais enregistré.";
const USER_NOT_CREATED = "Problème lors de l'enregistrement.";
const LOGIN_FAIL = "Vos identifiants ne sont pas reconnus, veuillez rééssayer.";
const LOGIN_OK = "Vous êtes connecté.";
const LOGOUT_OK = "Vous avez été déconnecté.";
const WRONG_PWD_REENTRY = "Les deux mots de passe insérés ne correspondent pas, merci de réesayer.";
const USER_ALREADY_EXISTS = "Cet email est déjà utilisé.";
const ERROR_USER_NOT_FOUND = "Problème de récupération des informations de l'utilisateur.";
const USER_UPDATED = "Les modifications ont été enregistrées.";
const ACCESS_ERROR = "Vous devez être connecté et avoir les droits suffisants pour accéder à cette page.";

//used in blogpostController
const ERROR_BLOGPOST_NOT_FOUND = "Problème dans la récupération du post.";
const ERROR_BLOGPOST_CREATION = "Problème lors de l'enregistrement du blogpost.";
const BLOGPOST_CREATED = "Votre post a été enregistré.";
const NOT_OWNER = "Vous ne pouvez pas modifier ce blogpost.";
const BLOGPOST_NOT_FOUND = "Nous n'avons pas réussi à récupérer les information du blogpost demandé.";
const BLOGPOST_UPDATED = "Votre post a été mis à jour.";
const BLOGPOST_DELETED = "Le post a bien été supprimé.";

//used in commentController
const ERROR_COMMENT_NOT_FOUND = "Problème dans la récupération du commentaire.";
const ERROR_COMMENT_CREATION = "Problème lors de l'enregistrement du commentaire.";
const COMMENT_CREATED = "Votre commentaire a été enregistré, il sera visible dès sa validation par un modérateur.";
const NOT_OWNER_COMMENT = "Vous ne pouvez pas modifier ce commentaire.";
const COMMENT_NOT_FOUND = "Nous n'avons pas réussi à récupérer les information du commentaire demandé.";
const COMMENT_UPDATED = "Votre commentaire a été mis à jour, il sera visible dès sa validation par un modérateur.";
const COMMENT_NOT_UPDATED = "Problème lors de la mise à jour du commentaire.";
const VISIBILITY_UPDATED = "La visbilité du commentaire à été mise à jour.";
const COMMENT_DELETED = "Le commentaire a bien été supprimé.";

//used in contactController
const CONTACT_FORM_ERROR = "Problème dans la soumission du formulaire de contact, merci de réessayer.";
const CONTACT_FORM_OK = "Merci de m'avoir contacté, je reviens vers vous au plus vite.";
const MISSING_LASTNAME = "Merci d'indiquer votre nom.";
const MISSING_FIRSTNAME = "Merci d'indiquer votre prénom.";
const MISSING_EMAIL = "Merci de m'indiquer votre email.";
const MISSING_MESSAGE = "Merci d'ajouter un message'.";
const NOT_EMAIL_TYPE = "Le format de l'adresse mail n'est pas valide.";
const MAIL_SUBJECT = "Nouvelle soumission du formulaire de contact sur mon blog.";
const LASTNAME_FIELD_NAME = "Nom: ";
const FIRSTNAME_FIELD_NAME = "Prénom: ";
const EMAIL_FIELD_NAME = "Email: ";
const MESSAGE_FIELD_NAME = "Message: ";
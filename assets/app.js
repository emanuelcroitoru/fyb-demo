/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import Routing from 'fos-router';
import $ from 'jquery';
$(document).ready(function() {

    // $(".delete-user").click(function (e) {
    //     const userId = $(this).data("id");
    //     e.preventDefault();
    //     $.ajax({
    //         type: 'DELETE',
    //         dataType: 'json',
    //         url: Routing.generate('api_user_delete', {id: userId}),
    //     }).done((res) => {
    //         $(this).closest('div.col').fadeOut('slow', function() {
    //             $(this).remove();
    //         });
    //     }).fail((err) => {
    //         console.log(err);
    //     })
    // });

    $(document).on('click', '.delete-project', function (e) {
        const projectId = $(this).data("id");
        e.preventDefault();

        $.ajax({
            type: 'DELETE',
            dataType: 'json',
            url: Routing.generate('api_project_delete', {id: projectId}),
        }).done((res) => {
            const cardBody = $(this).parents('.card-body');
            const noOfProjectsElement = cardBody.find('.no-of-projects');
            const userProjectsButton= cardBody.find('.user-projects');
            let noOfProjects = parseInt(noOfProjectsElement.text().trim());
            noOfProjects--;
            noOfProjectsElement.text(noOfProjects);

            if (noOfProjects === 0) {
                $(this).closest('.card-body').fadeOut('slow', function() {
                    $(this).remove();
                });

                userProjectsButton.fadeOut('slow', function() {
                    $(this).remove();
                });
            }

            $(this).closest('li').fadeOut('slow', function() {
                $(this).remove();
            });
        }).fail((err) => {
            console.log(err);
        })
    });

    $(".delete-milestone").click(function (e) {
        const milestoneId = $(this).data("id");
        e.preventDefault();
        $.ajax({
            type: 'DELETE',
            dataType: 'json',
            url: Routing.generate('api_milestone_delete', {id: milestoneId}),
        }).done((res) => {
            $(this).closest('div.col').fadeOut('slow', function() {
                $(this).remove();
            });
        }).fail((err) => {
            console.log(err);
        })
    });

    $(".user-projects").click(function (e) {
        const userId = $(this).data("id");
        e.preventDefault();
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: Routing.generate('api_user_projects', {id: userId}),
            data: {id: userId}
        }).done((res) => {

            const targetDiv = $(`#collapseExample_${userId} .card.card-body`);

            targetDiv.empty();

            const ul = $('<ul class="list-group">');

            $.each(res, function(index, item) {
                const viewProjectUrl = Routing.generate('app_project_milestones', {id: item.id});

                let link = $('<a>')
                    .text(item.title)
                    .attr('href', viewProjectUrl);

                let deleteButton = $(`<buttton class="btn btn-sm btn-outline-danger delete-project" data-id="${item.id}">`).text('delete');
                let li = $('<li class="list-group-item d-flex justify-content-between align-items-center">')
                    .append(link)
                    .append(deleteButton);

                ul.append(li);
            });

            targetDiv.append(ul);

        }).fail((err) => {
            console.log(err);
        })
    });
});

// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// any CSS you import will output into a single css file (app.css in this case)
import './styles/global.scss';

// start the Stimulus application
import './bootstrap';

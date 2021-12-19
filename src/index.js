const URL = "http://localhost/crud-api-php/api/tasks";

const frm = document.getElementById("frm");

const spanLoader = document.getElementById("loader");
const spanSave = document.getElementById("save");

const listContent = document.getElementById("list-content");
const listItem = document.getElementById("list-item").content;

let tasks = [];
let objectUpdate = {};
let isEdit = false;

// ANIMATION

const ChangeClass = () => {
  spanLoader.classList.toggle("visually-hidden");
  spanSave.classList.toggle("visually-hidden");
};

//SEARCH

const searchTask = (id) => {
  return tasks.find((item) => item.id === id);
};

// LIST

const Build = () => {
  listContent.innerHTML = "";
  tasks.map((item, index) => RenderItem({ ...item, index }));
};

const RenderItem = ({ title, description, index, id }) => {
  const clone = listItem.cloneNode(true);

  clone.querySelector("[data-index]").textContent = index + 1;
  clone.querySelector("[data-title]").textContent = title;
  clone.querySelector("[data-desc]").textContent = description;

  clone.querySelector(".edit").dataset.id = id;
  clone.querySelector(".delete").dataset.id = id;

  listContent.appendChild(clone);
};

// API

const getTasks = async () => {
  const response = await fetch(URL);
  const data = await response.json();
  tasks = data;
  Build();
};

const postTasks = async (object) => {
  const response = await fetch(URL, {
    method: "POST",
    body: JSON.stringify(object),
  });
  if (response.status === 200) {
    console.log("Registrado correctamente");
  } else {
    console.log("Fallo algo");
  }
};

const putTasks = async (id, object) => {
  const response = await fetch(`${URL}/${id}`, {
    method: "PUT",
    body: JSON.stringify(object),
  });
  if (response.status === 200) {
    console.log("Actualizado correctamente");
  } else {
    console.log("Fallo algo");
  }
};

const deleteTask = async (id) => {
  const response = await fetch(`${URL}/${id}`, { method: "DELETE" });
  if (response.status === 200) {
    console.log("Eliminado correctamente");
  } else {
    console.log("Fallo algo");
  }
};

// DOM

const FillForm = () => {
  frm.title.value = objectUpdate.title;
  frm.description.value = objectUpdate.description;
};

/**
 *
 * @param {Event} ev
 */
const handleSubmit = async (ev) => {
  ev.preventDefault();

  let object = {
    title: frm.title.value,
    description: frm.description.value,
  };

  if (Auth(object)) {
    ChangeClass();
    if (isEdit) {
      await putTasks(objectUpdate.id, object);
      isEdit = false;
      objectUpdate = {};
    } else {
      await postTasks(object);
    }
    await getTasks();
    ChangeClass();
    frm.reset();
  }
};

/**
 *
 * @param {Event} ev
 */
const handleClick = (ev) => {
  if (ev.target.classList.contains("delete")) {
    if (confirm("¿Deseas Eliminarlo?")) {
      deleteTask(ev.target.dataset.id);
      getTasks();
    }
  }

  if (ev.target.classList.contains("edit")) {
    let found = searchTask(ev.target.dataset.id);
    objectUpdate = found;
    isEdit = true;
    FillForm();
  }

  ev.stopPropagation();
};

const Auth = (object) => {
  if (!object.title) {
    console.log("Titulo es importante");
    return false;
  }
  if (!object.description) {
    console.log("Description es importante");
    return false;
  }
  return true;
};

frm.addEventListener("submit", handleSubmit);
listContent.addEventListener("click", handleClick);

getTasks();

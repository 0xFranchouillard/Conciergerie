
let groupGrass = new THREE.Group();

function createFreshGrass(){

	// grass
	var textureGrass = new THREE.TextureLoader().load('textures/fence2.png');
	var geoGrass = new THREE.BoxBufferGeometry(50,50,50);
	var materialGrass = new THREE.MeshBasicMaterial({map:textureGrass } );

	grassBlock = new THREE.Mesh(geoGrass,materialGrass);

	groupGrass.add(grassBlock)
}

function cutGrass(){



}
